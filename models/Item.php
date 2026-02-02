<?php
/**
 * Item Model
 *
 * Manages inventory items
 */

require_once __DIR__ . '/BaseModel.php';

class Item extends BaseModel
{
    protected static $table = 'items';
    protected static $primaryKey = 'item_id';
    protected static $fillable = [
        'sku',
        'item_name',
        'category_id',
        'uom_id',
        'description',
        'minimum_stock_level',
        'reorder_level',
        'unit_cost',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Find item by SKU
     *
     * @param string $sku SKU code
     * @return array|false
     */
    public static function findBySku($sku)
    {
        return static::first(['sku' => $sku]);
    }

    /**
     * Search items by term (full-text search on name and description)
     *
     * @param string $searchTerm Search term
     * @param int|null $categoryId Filter by category
     * @param int $limit Maximum results
     * @return array
     */
    public static function search($searchTerm, $categoryId = null, $limit = 50)
    {
        $sql = "SELECT i.*, ic.category_name, u.uom_name, u.uom_code
                FROM " . static::$table . " i
                LEFT JOIN item_categories ic ON i.category_id = ic.category_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                WHERE (i.item_name LIKE ? OR i.sku LIKE ? OR i.description LIKE ?)
                AND i.deleted_at IS NULL";

        $params = ["%{$searchTerm}%", "%{$searchTerm}%", "%{$searchTerm}%"];

        if ($categoryId !== null) {
            $sql .= " AND i.category_id = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY i.item_name ASC LIMIT ?";
        $params[] = $limit;

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get items below minimum stock level (low stock alert)
     *
     * @param int|null $storeId Filter by store
     * @return array
     */
    public static function getLowStockItems($storeId = null)
    {
        $sql = "SELECT i.*, ic.category_name, sl.quantity_on_hand, sl.quantity_available,
                       sl.store_id, s.store_name
                FROM " . static::$table . " i
                INNER JOIN stock_levels sl ON i.item_id = sl.item_id
                INNER JOIN stores s ON sl.store_id = s.store_id
                LEFT JOIN item_categories ic ON i.category_id = ic.category_id
                WHERE sl.quantity_on_hand <= i.minimum_stock_level
                AND i.is_active = 1
                AND i.deleted_at IS NULL";

        $params = [];

        if ($storeId !== null) {
            $sql .= " AND sl.store_id = ?";
            $params[] = $storeId;
        }

        $sql .= " ORDER BY sl.quantity_on_hand ASC, i.item_name ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get item with category details
     *
     * @param int $itemId Item ID
     * @return array|false
     */
    public static function getWithCategory($itemId)
    {
        $sql = "SELECT i.*, ic.category_name, ic.category_code, ic.is_fuel_category,
                       u.uom_name, u.uom_code
                FROM " . static::$table . " i
                LEFT JOIN item_categories ic ON i.category_id = ic.category_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                WHERE i.item_id = ?
                AND i.deleted_at IS NULL";

        return Database::fetchOne($sql, [$itemId]);
    }

    /**
     * Get item with stock levels across all stores
     *
     * @param int $itemId Item ID
     * @param int|null $storeId Specific store (null for all stores)
     * @return array|false
     */
    public static function getWithStock($itemId, $storeId = null)
    {
        $sql = "SELECT i.*, ic.category_name, u.uom_name,
                       sl.stock_id, sl.store_id, s.store_name,
                       sl.quantity_on_hand, sl.quantity_reserved, sl.quantity_available
                FROM " . static::$table . " i
                LEFT JOIN item_categories ic ON i.category_id = ic.category_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                LEFT JOIN stock_levels sl ON i.item_id = sl.item_id
                LEFT JOIN stores s ON sl.store_id = s.store_id
                WHERE i.item_id = ?
                AND i.deleted_at IS NULL";

        $params = [$itemId];

        if ($storeId !== null) {
            $sql .= " AND sl.store_id = ?";
            $params[] = $storeId;
        }

        if ($storeId !== null) {
            return Database::fetchOne($sql, $params);
        }

        return Database::fetchAll($sql, $params);
    }

    /**
     * Validate SKU uniqueness
     *
     * @param string $sku SKU code
     * @param int|null $excludeId Exclude this item ID from check
     * @return bool True if unique, false if exists
     */
    public static function validateSkuUnique($sku, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table . "
                WHERE sku = ?
                AND deleted_at IS NULL";
        $params = [$sku];

        if ($excludeId !== null) {
            $sql .= " AND item_id != ?";
            $params[] = $excludeId;
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] == 0;
    }

    /**
     * Get items by category
     *
     * @param int $categoryId Category ID
     * @return array
     */
    public static function getItemsByCategory($categoryId)
    {
        return static::where(['category_id' => $categoryId, 'is_active' => 1]);
    }

    /**
     * Get only active items
     *
     * @param array $conditions Additional conditions
     * @return array
     */
    public static function getActiveItems($conditions = [])
    {
        $conditions['is_active'] = 1;
        return static::where($conditions, false);
    }

    /**
     * Get all items with details for listing
     *
     * @param array $filters Filters (category_id, search, is_active)
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array Paginated result
     */
    public static function getAllWithDetails($filters = [], $page = 1, $perPage = 20)
    {
        $sql = "SELECT i.*, ic.category_name, u.uom_name, u.uom_code,
                       COALESCE(SUM(sl.quantity_on_hand), 0) as total_stock
                FROM " . static::$table . " i
                LEFT JOIN item_categories ic ON i.category_id = ic.category_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                LEFT JOIN stock_levels sl ON i.item_id = sl.item_id
                WHERE i.deleted_at IS NULL";

        $params = [];

        // Apply filters
        if (!empty($filters['category_id'])) {
            $sql .= " AND i.category_id = ?";
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (i.item_name LIKE ? OR i.sku LIKE ?)";
            $searchPattern = "%{$filters['search']}%";
            $params[] = $searchPattern;
            $params[] = $searchPattern;
        }

        if (isset($filters['is_active'])) {
            $sql .= " AND i.is_active = ?";
            $params[] = $filters['is_active'];
        }

        $sql .= " GROUP BY i.item_id";
        $sql .= " ORDER BY i.item_name ASC";

        // Get total count
        $countSql = "SELECT COUNT(DISTINCT i.item_id) as count
                     FROM " . static::$table . " i
                     WHERE i.deleted_at IS NULL";

        $countParams = [];
        if (!empty($filters['category_id'])) {
            $countSql .= " AND i.category_id = ?";
            $countParams[] = $filters['category_id'];
        }
        if (!empty($filters['search'])) {
            $countSql .= " AND (i.item_name LIKE ? OR i.sku LIKE ?)";
            $searchPattern = "%{$filters['search']}%";
            $countParams[] = $searchPattern;
            $countParams[] = $searchPattern;
        }
        if (isset($filters['is_active'])) {
            $countSql .= " AND i.is_active = ?";
            $countParams[] = $filters['is_active'];
        }

        $totalResult = Database::fetchOne($countSql, $countParams);
        $total = $totalResult['count'];

        // Pagination
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $data = Database::fetchAll($sql, $params);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
            'has_more' => $page < ceil($total / $perPage)
        ];
    }

    /**
     * Check if item has stock in any store
     *
     * @param int $itemId Item ID
     * @return bool
     */
    public static function hasStock($itemId)
    {
        $sql = "SELECT COALESCE(SUM(quantity_on_hand), 0) as total_stock
                FROM stock_levels
                WHERE item_id = ?";

        $result = Database::fetchOne($sql, [$itemId]);
        return $result['total_stock'] > 0;
    }
}
