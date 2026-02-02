<?php
/**
 * ItemCategory Model
 *
 * Manages item categories with hierarchical support
 */

require_once __DIR__ . '/BaseModel.php';

class ItemCategory extends BaseModel
{
    protected static $table = 'item_categories';
    protected static $primaryKey = 'category_id';
    protected static $fillable = [
        'category_name',
        'category_code',
        'parent_category_id',
        'is_fuel_category',
        'description'
    ];

    /**
     * Get category tree structure
     *
     * @param int|null $parentId Parent category ID (null for root)
     * @return array Hierarchical category tree
     */
    public static function getTree($parentId = null)
    {
        $sql = "SELECT * FROM " . static::$table . "
                WHERE deleted_at IS NULL";

        if ($parentId === null) {
            $sql .= " AND parent_category_id IS NULL";
            $categories = Database::fetchAll($sql);
        } else {
            $sql .= " AND parent_category_id = ?";
            $categories = Database::fetchAll($sql, [$parentId]);
        }

        // Recursively get children for each category
        foreach ($categories as &$category) {
            $category['children'] = static::getTree($category['category_id']);
        }

        return $categories;
    }

    /**
     * Get direct child categories
     *
     * @param int $categoryId Category ID
     * @return array
     */
    public static function getChildren($categoryId)
    {
        return static::where(['parent_category_id' => $categoryId]);
    }

    /**
     * Check if category is a fuel category
     *
     * @param int $categoryId Category ID
     * @return bool
     */
    public static function isFuelCategory($categoryId)
    {
        $category = static::find($categoryId);
        return $category && $category['is_fuel_category'] == 1;
    }

    /**
     * Get category breadcrumb path
     *
     * @param int $categoryId Category ID
     * @return array Array of categories from root to current
     */
    public static function getCategoryPath($categoryId)
    {
        $path = [];
        $currentId = $categoryId;

        while ($currentId !== null) {
            $category = static::find($currentId);
            if (!$category) {
                break;
            }

            array_unshift($path, $category);
            $currentId = $category['parent_category_id'];
        }

        return $path;
    }

    /**
     * Get root categories (top-level)
     *
     * @return array
     */
    public static function getRootCategories()
    {
        $sql = "SELECT * FROM " . static::$table . "
                WHERE parent_category_id IS NULL
                AND deleted_at IS NULL
                ORDER BY category_name ASC";

        return Database::fetchAll($sql);
    }

    /**
     * Get all categories as flat list for dropdowns
     *
     * @param bool $includePath Include parent path in name
     * @return array
     */
    public static function getAllForDropdown($includePath = true)
    {
        if (!$includePath) {
            return static::all([], false, 'category_name ASC');
        }

        // Get all categories with path
        $allCategories = static::all([], false, 'category_name ASC');
        $result = [];

        foreach ($allCategories as $category) {
            $path = static::getCategoryPath($category['category_id']);
            $pathNames = array_map(function($c) { return $c['category_name']; }, $path);
            $category['display_name'] = implode(' > ', $pathNames);
            $result[] = $category;
        }

        return $result;
    }

    /**
     * Validate category code uniqueness
     *
     * @param string $code Category code
     * @param int|null $excludeId Exclude this category ID from check
     * @return bool True if unique, false if exists
     */
    public static function validateCodeUnique($code, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table . "
                WHERE category_code = ?
                AND deleted_at IS NULL";
        $params = [$code];

        if ($excludeId !== null) {
            $sql .= " AND category_id != ?";
            $params[] = $excludeId;
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] == 0;
    }
}
