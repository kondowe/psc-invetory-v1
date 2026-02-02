<?php
/**
 * ItemController
 *
 * Handles inventory item management
 */

require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/ItemCategory.php';
require_once __DIR__ . '/../models/UnitOfMeasure.php';
require_once __DIR__ . '/../models/Store.php';

class ItemController
{
    /**
     * List all items
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $filters = [
            'search' => $_GET['search'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'is_active' => isset($_GET['is_active']) ? (int)$_GET['is_active'] : null
        ];

        $result = Item::getAllWithDetails($filters, $page);
        $categories = ItemCategory::getAllForDropdown();

        Response::view('inventory/index', [
            'items' => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }

    /**
     * Show create item form
     */
    public function create()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $categories = ItemCategory::getAllForDropdown();
        $uoms = UnitOfMeasure::getActiveUnits();

        Response::view('inventory/create', [
            'categories' => $categories,
            'uoms' => $uoms
        ]);
    }

    /**
     * Store new item
     */
    public function store()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        // Sanitize input
        $data = [
            'sku' => Security::sanitize($_POST['sku'] ?? '', 'string'),
            'item_name' => Security::sanitize($_POST['item_name'] ?? '', 'string'),
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'uom_id' => (int)($_POST['uom_id'] ?? 0),
            'description' => Security::sanitize($_POST['description'] ?? '', 'string'),
            'minimum_stock_level' => (float)($_POST['minimum_stock_level'] ?? 0),
            'reorder_level' => (float)($_POST['reorder_level'] ?? 0),
            'unit_cost' => (float)($_POST['unit_cost'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Validate
        $validator = new Validator();
        $valid = $validator->validate($data, [
            'sku' => 'required|min:2|max:50',
            'item_name' => 'required|min:2|max:200',
            'category_id' => 'required',
            'uom_id' => 'required'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/item/create');
        }

        // Check SKU uniqueness
        if (!Item::validateSkuUnique($data['sku'])) {
            Session::flash('error', 'SKU already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/item/create');
        }

        try {
            $itemId = Item::create($data);

            Logger::logActivity(
                Auth::id(),
                'item_create',
                "Created item: {$data['item_name']} ({$data['sku']})"
            );

            Session::flash('success', 'Item created successfully');
            Response::redirect('/item');

        } catch (Exception $e) {
            Logger::error('Item creation failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to create item: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/item/create');
        }
    }

    /**
     * Show edit item form
     */
    public function edit($itemId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $item = Item::find($itemId);

        if (!$item) {
            Session::flash('error', 'Item not found');
            Response::redirect('/item');
        }

        $categories = ItemCategory::getAllForDropdown();
        $uoms = UnitOfMeasure::getActiveUnits();

        Response::view('inventory/edit', [
            'item' => $item,
            'categories' => $categories,
            'uoms' => $uoms
        ]);
    }

    /**
     * Update item
     */
    public function update($itemId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $item = Item::find($itemId);

        if (!$item) {
            Session::flash('error', 'Item not found');
            Response::redirect('/item');
        }

        // Sanitize input
        $data = [
            'sku' => Security::sanitize($_POST['sku'] ?? '', 'string'),
            'item_name' => Security::sanitize($_POST['item_name'] ?? '', 'string'),
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'uom_id' => (int)($_POST['uom_id'] ?? 0),
            'description' => Security::sanitize($_POST['description'] ?? '', 'string'),
            'minimum_stock_level' => (float)($_POST['minimum_stock_level'] ?? 0),
            'reorder_level' => (float)($_POST['reorder_level'] ?? 0),
            'unit_cost' => (float)($_POST['unit_cost'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        // Validate
        $validator = new Validator();
        $valid = $validator->validate($data, [
            'sku' => 'required|min:2|max:50',
            'item_name' => 'required|min:2|max:200',
            'category_id' => 'required',
            'uom_id' => 'required'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/item/edit/' . $itemId);
        }

        // Check SKU uniqueness
        if (!Item::validateSkuUnique($data['sku'], $itemId)) {
            Session::flash('error', 'SKU already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/item/edit/' . $itemId);
        }

        try {
            Item::update($itemId, $data);

            Logger::logActivity(
                Auth::id(),
                'item_update',
                "Updated item: {$data['item_name']} (ID: {$itemId})"
            );

            Session::flash('success', 'Item updated successfully');
            Response::redirect('/item');

        } catch (Exception $e) {
            Logger::error('Item update failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to update item: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/item/edit/' . $itemId);
        }
    }

    /**
     * Delete item
     */
    public function delete($itemId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $item = Item::find($itemId);

        if (!$item) {
            Response::error('Item not found');
        }

        // Check if item has stock or transactions
        if (Item::hasStock($itemId)) {
            Response::error("Cannot delete item with existing stock. Please adjust stock to zero first.");
        }

        try {
            Item::delete($itemId);

            Logger::logActivity(
                Auth::id(),
                'item_delete',
                "Deleted item: {$item['item_name']} (ID: {$itemId})"
            );

            Response::success(['message' => 'Item deleted successfully']);

        } catch (Exception $e) {
            Logger::error('Item deletion failed: ' . $e->getMessage());
            Response::error('Failed to delete item: ' . $e->getMessage());
        }
    }

    /**
     * View item details
     */
    public function view($itemId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $item = Item::getWithCategory($itemId);

        if (!$item) {
            Session::flash('error', 'Item not found');
            Response::redirect('/item');
        }

        $stockLevels = Item::getWithStock($itemId);

        Response::view('inventory/view', [
            'item' => $item,
            'stockLevels' => $stockLevels
        ]);
    }

    /**
     * View stock levels across all items
     */
    public function stockLevels()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $storeId = $_GET['store_id'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $search = $_GET['search'] ?? null;

        $sql = "SELECT i.item_id, i.sku, i.item_name, ic.category_name, u.uom_code,
                       s.store_name, sl.quantity_on_hand, sl.quantity_reserved, sl.quantity_available,
                       i.minimum_stock_level
                FROM items i
                JOIN item_categories ic ON i.category_id = ic.category_id
                JOIN units_of_measure u ON i.uom_id = u.uom_id
                LEFT JOIN stock_levels sl ON i.item_id = sl.item_id
                LEFT JOIN stores s ON sl.store_id = s.store_id
                WHERE i.deleted_at IS NULL";

        $params = [];
        if ($storeId) {
            $sql .= " AND sl.store_id = ?";
            $params[] = $storeId;
        }
        if ($categoryId) {
            $sql .= " AND i.category_id = ?";
            $params[] = $categoryId;
        }
        if ($search) {
            $sql .= " AND (i.item_name LIKE ? OR i.sku LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY i.item_name ASC, s.store_name ASC";
        
        $stocks = Database::fetchAll($sql, $params);
        $stores = Store::getActiveStores();
        $categories = ItemCategory::getAllForDropdown();

        Response::view('inventory/stock_levels', [
            'stocks' => $stocks,
            'stores' => $stores,
            'categories' => $categories,
            'filters' => [
                'store_id' => $storeId,
                'category_id' => $categoryId,
                'search' => $search
            ]
        ]);
    }

    /**
     * View stock movements history
     */
    public function movements()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $itemId = $_GET['item_id'] ?? null;
        $storeId = $_GET['store_id'] ?? null;
        $type = $_GET['type'] ?? null;

        $sql = "SELECT m.*, i.item_name, i.sku, s.store_name, u.full_name as performer_name
                FROM stock_movements m
                JOIN items i ON m.item_id = i.item_id
                JOIN stores s ON m.store_id = s.store_id
                JOIN users u ON m.performed_by_user_id = u.user_id
                WHERE 1=1";

        $params = [];
        if ($itemId) {
            $sql .= " AND m.item_id = ?";
            $params[] = $itemId;
        }
        if ($storeId) {
            $sql .= " AND m.store_id = ?";
            $params[] = $storeId;
        }
        if ($type) {
            $sql .= " AND m.movement_type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY m.movement_date DESC LIMIT 100";
        
        $movements = Database::fetchAll($sql, $params);
        $items = Item::all([], false, 'item_name ASC');
        $stores = Store::getActiveStores();

        Response::view('inventory/movements', [
            'movements' => $movements,
            'items' => $items,
            'stores' => $stores,
            'filters' => [
                'item_id' => $itemId,
                'store_id' => $storeId,
                'type' => $type
            ]
        ]);
    }
}
