<?php
/**
 * SupplierController
 *
 * Handles supplier management
 */

require_once __DIR__ . '/../models/Supplier.php';

class SupplierController
{
    /**
     * List all suppliers
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $suppliers = Supplier::all([], false, 'supplier_name ASC');

        Response::view('suppliers/index', [
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Show create supplier form
     */
    public function create()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        Response::view('suppliers/create');
    }

    /**
     * Store new supplier
     */
    public function store()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $data = [
            'supplier_name' => Security::sanitize($_POST['supplier_name'] ?? '', 'string'),
            'supplier_code' => Security::sanitize($_POST['supplier_code'] ?? '', 'string'),
            'contact_person' => Security::sanitize($_POST['contact_person'] ?? '', 'string'),
            'email' => Security::sanitize($_POST['email'] ?? '', 'email'),
            'phone' => Security::sanitize($_POST['phone'] ?? '', 'string'),
            'address' => Security::sanitize($_POST['address'] ?? '', 'string'),
            'supplier_type' => $_POST['supplier_type'] ?? 'general',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $validator = new Validator();
        $valid = $validator->validate($data, [
            'supplier_name' => 'required|min:2|max:200',
            'supplier_code' => 'required|min:2|max:50',
            'email' => 'email'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/create');
        }

        if (!Supplier::validateCodeUnique($data['supplier_code'])) {
            Session::flash('error', 'Supplier code already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/create');
        }

        try {
            Supplier::create($data);

            Logger::logActivity(
                Auth::id(),
                'supplier_create',
                "Created supplier: {$data['supplier_name']} ({$data['supplier_code']})"
            );

            Session::flash('success', 'Supplier created successfully');
            Response::redirect('/supplier');

        } catch (Exception $e) {
            Logger::error('Supplier creation failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to create supplier: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/create');
        }
    }

    /**
     * Show edit supplier form
     */
    public function edit($supplierId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            Session::flash('error', 'Supplier not found');
            Response::redirect('/supplier');
        }

        Response::view('suppliers/edit', [
            'supplier' => $supplier
        ]);
    }

    /**
     * Update supplier
     */
    public function update($supplierId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            Session::flash('error', 'Supplier not found');
            Response::redirect('/supplier');
        }

        $data = [
            'supplier_name' => Security::sanitize($_POST['supplier_name'] ?? '', 'string'),
            'supplier_code' => Security::sanitize($_POST['supplier_code'] ?? '', 'string'),
            'contact_person' => Security::sanitize($_POST['contact_person'] ?? '', 'string'),
            'email' => Security::sanitize($_POST['email'] ?? '', 'email'),
            'phone' => Security::sanitize($_POST['phone'] ?? '', 'string'),
            'address' => Security::sanitize($_POST['address'] ?? '', 'string'),
            'supplier_type' => $_POST['supplier_type'] ?? 'general',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $validator = new Validator();
        $valid = $validator->validate($data, [
            'supplier_name' => 'required|min:2|max:200',
            'supplier_code' => 'required|min:2|max:50',
            'email' => 'email'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/edit/' . $supplierId);
        }

        if (!Supplier::validateCodeUnique($data['supplier_code'], $supplierId)) {
            Session::flash('error', 'Supplier code already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/edit/' . $supplierId);
        }

        try {
            Supplier::update($supplierId, $data);

            Logger::logActivity(
                Auth::id(),
                'supplier_update',
                "Updated supplier: {$data['supplier_name']} (ID: {$supplierId})"
            );

            Session::flash('success', 'Supplier updated successfully');
            Response::redirect('/supplier');

        } catch (Exception $e) {
            Logger::error('Supplier update failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to update supplier: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/supplier/edit/' . $supplierId);
        }
    }

    /**
     * Delete supplier
     */
    public function delete($supplierId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            Response::error('Supplier not found');
        }

        // Check for related GRVs
        $grvCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM goods_received_vouchers WHERE supplier_id = ?",
            [$supplierId]
        )['count'];

        if ($grvCount > 0) {
            Response::error("Cannot delete supplier with {$grvCount} transactions. Deactivate them instead.");
        }

        try {
            Supplier::delete($supplierId);

            Logger::logActivity(
                Auth::id(),
                'supplier_delete',
                "Deleted supplier: {$supplier['supplier_name']} (ID: {$supplierId})"
            );

            Response::success(['message' => 'Supplier deleted successfully']);

        } catch (Exception $e) {
            Logger::error('Supplier deletion failed: ' . $e->getMessage());
            Response::error('Failed to delete supplier: ' . $e->getMessage());
        }
    }

    /**
     * View supplier details
     */
    public function view($supplierId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            Session::flash('error', 'Supplier not found');
            Response::redirect('/supplier');
        }

        // Get recent GRVs from this supplier
        $sql = "SELECT grv.*, s.store_name, u.full_name as receiver_name
                FROM goods_received_vouchers grv
                JOIN stores s ON grv.store_id = s.store_id
                JOIN users u ON grv.received_by_user_id = u.user_id
                WHERE grv.supplier_id = ?
                ORDER BY grv.received_date DESC
                LIMIT 10";
        
        $recentGrvs = Database::fetchAll($sql, [$supplierId]);

        Response::view('suppliers/view', [
            'supplier' => $supplier,
            'recentGrvs' => $recentGrvs
        ]);
    }
}
