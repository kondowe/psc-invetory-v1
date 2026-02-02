<?php
/**
 * GRVController
 *
 * Handles Goods Received Vouchers workflow
 */

require_once __DIR__ . '/../models/GoodsReceivedVoucher.php';
require_once __DIR__ . '/../models/GRVItem.php';
require_once __DIR__ . '/../models/Supplier.php';
require_once __DIR__ . '/../models/Store.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/StockMovement.php';

class GRVController
{
    /**
     * List all GRVs
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('grv.view');

        $status = $_GET['status'] ?? null;
        $conditions = [];
        if ($status) {
            $conditions['status'] = $status;
        }

        $sql = "SELECT grv.*, s.supplier_name, st.store_name, u.full_name as receiver_name
                FROM goods_received_vouchers grv
                JOIN suppliers s ON grv.supplier_id = s.supplier_id
                JOIN stores st ON grv.store_id = st.store_id
                JOIN users u ON grv.received_by_user_id = u.user_id
                WHERE 1=1";
        
        $params = [];
        if ($status) {
            $sql .= " AND grv.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY grv.created_at DESC";
        $grvs = Database::fetchAll($sql, $params);

        Response::view('grv/index', [
            'grvs' => $grvs,
            'currentStatus' => $status
        ]);
    }

    /**
     * List only pending GRVs
     */
    public function pending()
    {
        $_GET['status'] = 'pending_approval';
        return $this->index();
    }

    /**
     * Show create GRV form
     */
    public function create()
    {
        Auth::requireAuth();
        RBAC::require('grv.create');

        $suppliers = Supplier::getActive();
        $stores = Store::getActiveStores();
        
        // Fetch items with category info
        $sql = "SELECT i.*, ic.is_fuel_category FROM items i 
                JOIN item_categories ic ON i.category_id = ic.category_id 
                WHERE i.is_active = 1 AND i.deleted_at IS NULL";
        $items = Database::fetchAll($sql);
        
        $categories = ItemCategory::getAllForDropdown();
        $uoms = UnitOfMeasure::getActiveUnits();
        $fuelTypes = Database::fetchAll("SELECT * FROM fuel_types");

        Response::view('grv/create', [
            'suppliers' => $suppliers,
            'stores' => $stores,
            'items' => $items,
            'categories' => $categories,
            'uoms' => $uoms,
            'fuelTypes' => $fuelTypes
        ]);
    }

    /**
     * Store new GRV (Draft)
     */
    public function store()
    {
        Auth::requireAuth();
        RBAC::require('grv.create');
        Security::checkCsrfToken();

        $headerData = [
            'grv_number' => GoodsReceivedVoucher::generateNumber(),
            'supplier_id' => (int)$_POST['supplier_id'],
            'store_id' => (int)$_POST['store_id'],
            'reference_number' => Security::sanitize($_POST['reference_number'] ?? '', 'string'),
            'reference_type' => Security::sanitize($_POST['reference_type'] ?? 'purchase_order', 'string'),
            'received_date' => $_POST['received_date'] ?? date('Y-m-d'),
            'received_by_user_id' => Auth::id(),
            'status' => isset($_POST['submit_for_approval']) ? 'pending_approval' : 'draft',
            'notes' => Security::sanitize($_POST['notes'] ?? '', 'string'),
            'total_value' => 0
        ];

        $items = $_POST['items'] ?? [];
        if (empty($items)) {
            Session::flash('error', 'At least one item is required');
            Response::redirect('/grv/create');
        }

        Database::beginTransaction();
        try {
            $grvId = GoodsReceivedVoucher::create($headerData);
            $totalValue = 0;

            foreach ($items as $item) {
                if (empty($item['quantity'])) continue;

                $itemId = !empty($item['item_id']) ? (int)$item['item_id'] : null;

                // Handle new item creation on the fly
                if (isset($item['is_new']) && $item['is_new'] == 'on') {
                    // Basic validation for new item
                    if (empty($item['new_sku']) || empty($item['new_name']) || empty($item['new_category_id']) || empty($item['new_uom_id'])) {
                        throw new Exception("New item details (SKU, Name, Category, UOM) are required for all new items.");
                    }

                    // Check if SKU already exists
                    if (!Item::validateSkuUnique($item['new_sku'])) {
                        throw new Exception("Item with SKU '{$item['new_sku']}' already exists.");
                    }

                    $itemId = Item::create([
                        'sku' => Security::sanitize($item['new_sku'], 'string'),
                        'item_name' => Security::sanitize($item['new_name'], 'string'),
                        'category_id' => (int)$item['new_category_id'],
                        'uom_id' => (int)$item['new_uom_id'],
                        'unit_cost' => (float)$item['unit_cost'],
                        'is_active' => 1
                    ]);
                }

                if (!$itemId) {
                    continue;
                }

                $lineTotal = (float)$item['quantity'] * (float)$item['unit_cost'];
                $totalValue += $lineTotal;

                $isFuel = (isset($item['is_fuel_coupon']) && $item['is_fuel_coupon'] == '1');
                $couponFrom = Security::sanitize($item['coupon_serial_from'] ?? '', 'string');
                $couponTo = Security::sanitize($item['coupon_serial_to'] ?? '', 'string');
                $couponValue = (float)($item['coupon_value'] ?? 0);
                $couponCount = 0;

                if ($isFuel && !empty($couponFrom) && !empty($couponTo)) {
                    // Basic numeric serial extraction if possible for count
                    $fromNum = (int)preg_replace('/[^0-9]/', '', $couponFrom);
                    $toNum = (int)preg_replace('/[^0-9]/', '', $couponTo);
                    if ($toNum >= $fromNum) {
                        $couponCount = ($toNum - $fromNum) + 1;
                    }
                }

                GRVItem::create([
                    'grv_id' => $grvId,
                    'item_id' => $itemId,
                    'quantity' => (float)$item['quantity'],
                    'unit_cost' => (float)$item['unit_cost'],
                    'batch_number' => Security::sanitize($item['batch_number'] ?? '', 'string'),
                    'expiry_date' => !empty($item['expiry_date']) ? $item['expiry_date'] : null,
                    'is_fuel_coupon' => $isFuel ? 1 : 0,
                    'fuel_type_id' => !empty($item['fuel_type_id']) ? (int)$item['fuel_type_id'] : null,
                    'coupon_serial_from' => $couponFrom,
                    'coupon_serial_to' => $couponTo,
                    'coupon_count' => $couponCount ?: null,
                    'coupon_value' => $couponValue ?: null,
                    'notes' => Security::sanitize($item['notes'] ?? '', 'string')
                ]);
            }

            GoodsReceivedVoucher::update($grvId, ['total_value' => $totalValue]);

            Database::commit();
            
            if (isset($_POST['submit_for_approval'])) {
                Logger::logActivity(Auth::id(), 'grv_submit', "Submitted GRV #{$headerData['grv_number']} for approval");
            } else {
                Logger::logActivity(Auth::id(), 'grv_create', "Created GRV #{$headerData['grv_number']} as draft");
            }

            Session::flash('success', 'GRV ' . (isset($_POST['submit_for_approval']) ? 'submitted' : 'saved') . ' successfully');
            Response::redirect('/grv/view/' . $grvId);

        } catch (Exception $e) {
            Database::rollBack();
            Logger::error('GRV creation failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to create GRV: ' . $e->getMessage());
            Response::redirect('/grv/create');
        }
    }

    /**
     * View GRV details
     */
    public function view($grvId)
    {
        Auth::requireAuth();
        RBAC::require('grv.view');

        $grv = GoodsReceivedVoucher::getWithDetails($grvId);
        if (!$grv) {
            Session::flash('error', 'GRV not found');
            Response::redirect('/grv');
        }

        $items = GRVItem::getByGrv($grvId);

        Response::view('grv/view', [
            'grv' => $grv,
            'items' => $items
        ]);
    }

    /**
     * Submit GRV for approval
     */
    public function submit($grvId)
    {
        Auth::requireAuth();
        RBAC::require('grv.create');

        $grv = GoodsReceivedVoucher::find($grvId);
        if (!$grv || $grv['status'] !== 'draft') {
            return; // Or handle error
        }

        GoodsReceivedVoucher::update($grvId, ['status' => 'pending_approval']);
        
        Logger::logActivity(Auth::id(), 'grv_submit', "Submitted GRV #{$grv['grv_number']} for approval");
    }

    /**
     * Approve GRV (Updates Stock)
     */
    public function approve($grvId)
    {
        Auth::requireAuth();
        RBAC::require('grv.approve'); // Assuming this permission exists
        Security::checkCsrfToken();

        $grv = GoodsReceivedVoucher::find($grvId);
        if (!$grv || $grv['status'] !== 'pending_approval') {
            Session::flash('error', 'Invalid GRV or status for approval');
            Response::redirect('/grv/view/' . $grvId);
        }

        $items = GRVItem::getByGrv($grvId);

        Database::beginTransaction();
        try {
            // 1. Update GRV status
            GoodsReceivedVoucher::update($grvId, [
                'status' => 'approved',
                'approved_by_user_id' => Auth::id(),
                'approved_date' => date('Y-m-d H:i:s')
            ]);

            // 2. Update Stock and Record Movements
            foreach ($items as $item) {
                StockMovement::record([
                    'item_id' => $item['item_id'],
                    'store_id' => $grv['store_id'],
                    'movement_type' => 'grv_in',
                    'quantity' => $item['quantity'],
                    'reference_type' => 'grv',
                    'reference_id' => $grvId,
                    'notes' => "Received via GRV #{$grv['grv_number']}"
                ]);

                // 3. Generate Individual Fuel Coupons if applicable
                if ($item['is_fuel_coupon'] && $item['coupon_serial_from'] && $item['coupon_serial_to']) {
                    $this->generateCoupons($grvId, $item);
                }
            }

            Database::commit();
            Session::flash('success', "GRV #{$grv['grv_number']} approved and coupons generated");
            Response::redirect(Security::url('/grv/view/' . $grvId));

        } catch (Exception $e) {
            Database::rollBack();
            Logger::error('GRV approval failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to approve GRV: ' . $e->getMessage());
            Response::redirect(Security::url('/grv/view/' . $grvId));
        }
    }

    /**
     * Helper to generate individual coupon records
     */
    private function generateCoupons($grvId, $item)
    {
        $from = $item['coupon_serial_from'];
        $to = $item['coupon_serial_to'];
        
        // Extract numeric part and prefix
        preg_match('/^([A-Za-z]*)([0-9]+)$/', $from, $fromMatches);
        preg_match('/^([A-Za-z]*)([0-9]+)$/', $to, $toMatches);
        
        if (!$fromMatches || !$toMatches || $fromMatches[1] !== $toMatches[1]) {
            // If prefix doesn't match or format is weird, just create From and To
            FuelCoupon::create([
                'coupon_serial_number' => $from,
                'item_id' => $item['item_id'],
                'fuel_type_id' => $item['fuel_type_id'],
                'coupon_value' => $item['coupon_value'],
                'status' => 'available',
                'grv_id' => $grvId,
                'expiry_date' => $item['expiry_date']
            ]);
            if ($from !== $to) {
                FuelCoupon::create([
                    'coupon_serial_number' => $to,
                    'item_id' => $item['item_id'],
                    'fuel_type_id' => $item['fuel_type_id'],
                    'coupon_value' => $item['coupon_value'],
                    'status' => 'available',
                    'grv_id' => $grvId,
                    'expiry_date' => $item['expiry_date']
                ]);
            }
            return;
        }

        $prefix = $fromMatches[1];
        $fromNum = (int)$fromMatches[2];
        $toNum = (int)$toMatches[2];
        $padLen = strlen($fromMatches[2]);

        for ($i = $fromNum; $i <= $toNum; $i++) {
            $serial = $prefix . str_pad($i, $padLen, '0', STR_PAD_LEFT);
            
            // Check for existing to avoid duplicates
            $exists = Database::fetchOne("SELECT coupon_id FROM fuel_coupons WHERE coupon_serial_number = ?", [$serial]);
            if ($exists) continue;

            FuelCoupon::create([
                'coupon_serial_number' => $serial,
                'item_id' => $item['item_id'],
                'fuel_type_id' => $item['fuel_type_id'],
                'coupon_value' => $item['coupon_value'],
                'status' => 'available',
                'grv_id' => $grvId,
                'expiry_date' => $item['expiry_date']
            ]);
        }
    }

    /**
     * Cancel GRV
     */
    public function cancel($grvId)
    {
        Auth::requireAuth();
        RBAC::require('grv.create');
        Security::checkCsrfToken();

        $grv = GoodsReceivedVoucher::find($grvId);
        if (!$grv || $grv['status'] == 'approved' || $grv['status'] == 'cancelled') {
            Session::flash('error', 'Cannot cancel this GRV');
            Response::redirect('/grv/view/' . $grvId);
        }

        GoodsReceivedVoucher::update($grvId, ['status' => 'cancelled']);
        Session::flash('success', 'GRV cancelled');
        Response::redirect('/grv/view/' . $grvId);
    }
}
