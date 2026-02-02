<?php
/**
 * IssueController
 */
require_once __DIR__ . '/../models/IssueVoucher.php';
require_once __DIR__ . '/../models/IssueVoucherItem.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/RequestItem.php';
require_once __DIR__ . '/../models/StockLevel.php';
require_once __DIR__ . '/../models/StockMovement.php';
require_once __DIR__ . '/../models/Store.php';

class IssueController
{
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $vouchers = IssueVoucher::all([], false, 'issue_date DESC');
        Response::view('issue/index', [
            'vouchers' => $vouchers
        ]);
    }

    public function pending()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        // Approved requests that haven't been fully issued
        $sql = "SELECT DISTINCT r.*, u.full_name as requester_name, d.department_name
                FROM requests r
                JOIN users u ON r.requester_user_id = u.user_id
                JOIN departments d ON r.department_id = d.department_id
                JOIN request_items ri ON r.request_id = ri.request_id
                WHERE r.status = 'approved'
                AND ri.quantity_issued < ri.quantity_approved";
        $requests = Database::fetchAll($sql);

        Response::view('issue/pending', [
            'requests' => $requests
        ]);
    }

    public function create($requestId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $request = Request::getWithDetails($requestId);
        if (!$request) Response::notFound();

        $items = RequestItem::getByRequest($requestId);
        $stores = Store::getActiveStores();

        // Fetch available coupons if it's a fuel request
        $availableCoupons = [];
        if ($request['request_type'] === 'fuel') {
            $sql = "SELECT c.*, i.item_name 
                    FROM fuel_coupons c
                    JOIN items i ON c.item_id = i.item_id
                    WHERE c.status = 'available' 
                    AND c.fuel_type_id = ?
                    AND (c.expiry_date IS NULL OR c.expiry_date >= CURRENT_DATE)
                    ORDER BY c.coupon_serial_number ASC";
            $availableCoupons = Database::fetchAll($sql, [$request['fuel_type_id']]);
        }

        Response::view('issue/create', [
            'request' => $request,
            'items' => $items,
            'stores' => $stores,
            'availableCoupons' => $availableCoupons
        ]);
    }

    public function store()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $requestId = (int)$_POST['request_id'];
        $storeId = (int)$_POST['store_id'];
        
        $items = $_POST['items'] ?? [];
        if (empty($items)) {
            Session::flash('error', 'No items selected for issuance');
            Response::back();
        }

        Database::beginTransaction();
        try {
            $voucherId = IssueVoucher::create([
                'issue_voucher_number' => IssueVoucher::generateNumber(),
                'request_id' => $requestId,
                'store_id' => $storeId,
                'issued_by_user_id' => Auth::id(),
                'received_by_name' => Security::sanitize($_POST['received_by_name'] ?? '', 'string'),
                'issue_date' => date('Y-m-d H:i:s'),
                'status' => 'issued',
                'notes' => Security::sanitize($_POST['notes'] ?? '', 'string')
            ]);

            $allIssued = true;
            foreach ($items as $reqItemId => $data) {
                $qtyToIssue = (float)($data['quantity'] ?? 0);
                if ($qtyToIssue <= 0) continue;

                $reqItem = RequestItem::find($reqItemId);
                
                // Record voucher item
                IssueVoucherItem::create([
                    'issue_voucher_id' => $voucherId,
                    'request_item_id' => $reqItemId,
                    'item_id' => $reqItem['item_id'],
                    'quantity_issued' => $qtyToIssue
                ]);

                // Update request item
                $newQtyIssued = $reqItem['quantity_issued'] + $qtyToIssue;
                RequestItem::update($reqItemId, [
                    'quantity_issued' => $newQtyIssued,
                    'status' => $newQtyIssued >= $reqItem['quantity_approved'] ? 'issued' : 'partially_issued'
                ]);

                if ($newQtyIssued < $reqItem['quantity_approved']) {
                    $allIssued = false;
                }

                // Update Stock and Record Movement (Only for inventory items)
                if (!$reqItem['is_custom']) {
                    StockMovement::record([
                        'item_id' => $reqItem['item_id'],
                        'store_id' => $storeId,
                        'movement_type' => 'issue_out',
                        'quantity' => -$qtyToIssue,
                        'reference_type' => 'issue_voucher',
                        'reference_id' => $voucherId,
                        'notes' => "Issued for Request #{$_POST['request_number']}"
                    ]);
                }
            }

            // Handle individual fuel coupons if any
            $selectedCoupons = $_POST['selected_coupons'] ?? [];
            if (!empty($selectedCoupons)) {
                $request = Request::find($requestId);
                foreach ($selectedCoupons as $couponId) {
                    // 1. Update coupon status
                    FuelCoupon::update((int)$couponId, [
                        'status' => 'issued',
                        'issued_in_issue_voucher_id' => $voucherId,
                        'issued_date' => date('Y-m-d H:i:s')
                    ]);

                    // 2. Record detailed issuance
                    Database::insert('fuel_coupon_issuance', [
                        'issue_voucher_id' => $voucherId,
                        'coupon_id' => (int)$couponId,
                        'request_id' => $requestId,
                        'vehicle_id' => $request['vehicle_id'] ?? null,
                        'issued_date' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // Update request status
            if ($allIssued) {
                Request::update($requestId, ['status' => 'issued']);
            } else {
                Request::update($requestId, ['status' => 'partially_issued']);
            }

            Database::commit();
            Session::flash('success', 'Issue voucher created and stock updated');
            Response::redirect('/issue');

        } catch (Exception $e) {
            Database::rollBack();
            Logger::error('Issuance failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to issue items: ' . $e->getMessage());
            Response::back();
        }
    }

    public function view($voucherId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $sql = "SELECT iv.*, r.request_number, s.store_name, u.full_name as issuer_name
                FROM issue_vouchers iv
                JOIN requests r ON iv.request_id = r.request_id
                JOIN stores s ON iv.store_id = s.store_id
                JOIN users u ON iv.issued_by_user_id = u.user_id
                WHERE iv.issue_voucher_id = ?";
        $voucher = Database::fetchOne($sql, [$voucherId]);
        if (!$voucher) Response::notFound();

        $sql = "SELECT ivi.*, i.item_name, i.sku, u.uom_code, ri.is_custom, ri.custom_item_name
                FROM issue_voucher_items ivi
                LEFT JOIN items i ON ivi.item_id = i.item_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                LEFT JOIN request_items ri ON ivi.request_item_id = ri.request_item_id
                WHERE ivi.issue_voucher_id = ?";
        $items = Database::fetchAll($sql, [$voucherId]);

        Response::view('issue/view', [
            'voucher' => $voucher,
            'items' => $items
        ]);
    }
}
