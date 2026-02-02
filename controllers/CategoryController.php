<?php
/**
 * CategoryController
 *
 * Handles item category management
 */

require_once __DIR__ . '/../models/ItemCategory.php';

class CategoryController
{
    /**
     * List all categories
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        // Get categories as tree structure
        $categories = ItemCategory::getTree();

        // Get flat list with item counts
        $sql = "SELECT c.category_id, c.category_name, c.category_code, c.parent_category_id,
                       c.is_fuel_category, COUNT(i.item_id) as item_count
                FROM item_categories c
                LEFT JOIN items i ON c.category_id = i.category_id AND i.deleted_at IS NULL
                WHERE c.deleted_at IS NULL
                GROUP BY c.category_id
                ORDER BY c.category_name ASC";

        $categoriesWithCounts = Database::fetchAll($sql);

        Response::view('categories/index', [
            'categories' => $categories,
            'categoriesWithCounts' => $categoriesWithCounts
        ]);
    }

    /**
     * Show create category form
     */
    public function create()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        // Get all categories for parent dropdown
        $categories = ItemCategory::getAllForDropdown();

        Response::view('categories/create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store new category
     */
    public function store()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        // Sanitize input
        $data = [
            'category_name' => Security::sanitize($_POST['category_name'] ?? '', 'string'),
            'category_code' => Security::sanitize($_POST['category_code'] ?? '', 'string'),
            'parent_category_id' => !empty($_POST['parent_category_id']) ? (int)$_POST['parent_category_id'] : null,
            'is_fuel_category' => isset($_POST['is_fuel_category']) ? 1 : 0,
            'description' => Security::sanitize($_POST['description'] ?? '', 'string')
        ];

        // Validate
        $validator = new Validator();
        $valid = $validator->validate($data, [
            'category_name' => 'required|min:2|max:100',
            'category_code' => 'required|alphanumeric|min:2|max:20',
            'description' => 'max:500'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/create');
        }

        // Check code uniqueness
        if (!ItemCategory::validateCodeUnique($data['category_code'])) {
            Session::flash('error', 'Category code already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/create');
        }

        try {
            $categoryId = ItemCategory::create($data);

            Logger::logActivity(
                Auth::id(),
                'category_create',
                "Created category: {$data['category_name']} ({$data['category_code']})"
            );

            Session::flash('success', 'Category created successfully');
            Response::redirect('/categories');

        } catch (Exception $e) {
            Logger::error('Category creation failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to create category: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/create');
        }
    }

    /**
     * Show edit category form
     */
    public function edit($categoryId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $category = ItemCategory::find($categoryId);

        if (!$category) {
            Session::flash('error', 'Category not found');
            Response::redirect('/categories');
        }

        // Get all categories except current one for parent dropdown
        $allCategories = ItemCategory::all();
        $categories = array_filter($allCategories, function($c) use ($categoryId) {
            return $c['category_id'] != $categoryId;
        });

        Response::view('categories/edit', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Update category
     */
    public function update($categoryId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $category = ItemCategory::find($categoryId);

        if (!$category) {
            Session::flash('error', 'Category not found');
            Response::redirect('/categories');
        }

        // Sanitize input
        $data = [
            'category_name' => Security::sanitize($_POST['category_name'] ?? '', 'string'),
            'category_code' => Security::sanitize($_POST['category_code'] ?? '', 'string'),
            'parent_category_id' => !empty($_POST['parent_category_id']) ? (int)$_POST['parent_category_id'] : null,
            'is_fuel_category' => isset($_POST['is_fuel_category']) ? 1 : 0,
            'description' => Security::sanitize($_POST['description'] ?? '', 'string')
        ];

        // Validate
        $validator = new Validator();
        $valid = $validator->validate($data, [
            'category_name' => 'required|min:2|max:100',
            'category_code' => 'required|alphanumeric|min:2|max:20',
            'description' => 'max:500'
        ]);

        if (!$valid) {
            Session::flash('error', 'Validation failed: ' . implode(', ', $validator->getErrors()));
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/edit/' . $categoryId);
        }

        // Check code uniqueness (excluding current category)
        if (!ItemCategory::validateCodeUnique($data['category_code'], $categoryId)) {
            Session::flash('error', 'Category code already exists');
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/edit/' . $categoryId);
        }

        // Prevent setting parent to itself or its descendants
        if ($data['parent_category_id'] == $categoryId) {
            Session::flash('error', 'Category cannot be its own parent');
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/edit/' . $categoryId);
        }

        try {
            ItemCategory::update($categoryId, $data);

            Logger::logActivity(
                Auth::id(),
                'category_update',
                "Updated category: {$data['category_name']} (ID: {$categoryId})"
            );

            Session::flash('success', 'Category updated successfully');
            Response::redirect('/categories');

        } catch (Exception $e) {
            Logger::error('Category update failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to update category: ' . $e->getMessage());
            Session::flash('old_input', $_POST);
            Response::redirect('/categories/edit/' . $categoryId);
        }
    }

    /**
     * Delete category
     */
    public function delete($categoryId)
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        $category = ItemCategory::find($categoryId);

        if (!$category) {
            Response::error('Category not found');
        }

        // Check if category has items
        $itemCount = Database::fetchOne(
            "SELECT COUNT(*) as count FROM items WHERE category_id = ? AND deleted_at IS NULL",
            [$categoryId]
        )['count'];

        if ($itemCount > 0) {
            Response::error("Cannot delete category with {$itemCount} items. Please reassign or delete items first.");
        }

        // Check if category has children
        $children = ItemCategory::getChildren($categoryId);
        if (count($children) > 0) {
            Response::error("Cannot delete category with " . count($children) . " sub-categories. Please delete or reassign sub-categories first.");
        }

        try {
            ItemCategory::delete($categoryId);

            Logger::logActivity(
                Auth::id(),
                'category_delete',
                "Deleted category: {$category['category_name']} (ID: {$categoryId})"
            );

            Response::success(['message' => 'Category deleted successfully']);

        } catch (Exception $e) {
            Logger::error('Category deletion failed: ' . $e->getMessage());
            Response::error('Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * API: Get category tree as JSON
     */
    public function apiGetTree()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');

        $tree = ItemCategory::getTree();
        Response::json(['success' => true, 'data' => $tree]);
    }
}
