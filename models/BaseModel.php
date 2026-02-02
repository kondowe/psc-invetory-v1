<?php
/**
 * BaseModel Class
 *
 * Abstract base model with CRUD operations and soft delete support
 * All models should extend this class
 */

abstract class BaseModel
{
    protected static $table = '';
    protected static $primaryKey = 'id';
    protected static $fillable = [];
    protected static $softDelete = true;

    /**
     * Find record by ID
     *
     * @param int $id Record ID
     * @param bool $includeSoftDeleted Include soft deleted records
     * @return array|false
     */
    public static function find($id, $includeSoftDeleted = false)
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?";

        if (static::$softDelete && !$includeSoftDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $sql .= " LIMIT 1";

        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Find all records
     *
     * @param array $conditions WHERE conditions
     * @param bool $includeSoftDeleted Include soft deleted records
     * @param string $orderBy ORDER BY clause
     * @param int $limit LIMIT
     * @param int $offset OFFSET
     * @return array
     */
    public static function all($conditions = [], $includeSoftDeleted = false, $orderBy = null, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM " . static::$table;
        $params = [];

        // Build WHERE clause
        $whereClauses = [];

        if (static::$softDelete && !$includeSoftDeleted) {
            $whereClauses[] = "deleted_at IS NULL";
        }

        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = ?";
            $params[] = $value;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        // ORDER BY
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        // LIMIT and OFFSET
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }

        return Database::fetchAll($sql, $params);
    }

    /**
     * Find records with WHERE clause
     *
     * @param array $conditions Associative array of field => value
     * @param bool $includeSoftDeleted Include soft deleted records
     * @param string $orderBy ORDER BY clause
     * @param int $limit LIMIT
     * @param int $offset OFFSET
     * @return array
     */
    public static function where($conditions, $includeSoftDeleted = false, $orderBy = null, $limit = null, $offset = null)
    {
        return static::all($conditions, $includeSoftDeleted, $orderBy, $limit, $offset);
    }

    /**
     * Find first record matching conditions
     *
     * @param array $conditions Conditions
     * @param bool $includeSoftDeleted Include soft deleted records
     * @return array|false
     */
    public static function first($conditions, $includeSoftDeleted = false)
    {
        $results = static::all($conditions, $includeSoftDeleted, null, 1);
        return $results[0] ?? false;
    }

    /**
     * Create new record
     *
     * @param array $data Data
     * @return int Last insert ID
     */
    public static function create($data)
    {
        // Filter only fillable fields
        if (!empty(static::$fillable)) {
            $data = array_intersect_key($data, array_flip(static::$fillable));
        }

        // Add timestamps if columns exist in fillable
        if (in_array('created_at', static::$fillable) && !isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(static::$table, $data);
    }

    /**
     * Update record
     *
     * @param int $id Record ID
     * @param array $data Data
     * @return int Number of affected rows
     */
    public static function update($id, $data)
    {
        // Filter only fillable fields
        if (!empty(static::$fillable)) {
            $data = array_intersect_key($data, array_flip(static::$fillable));
        }

        // Add updated_at timestamp if column exists in fillable
        if (in_array('updated_at', static::$fillable) && !isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return Database::update(static::$table, $data, static::$primaryKey . " = ?", [$id]);
    }

    /**
     * Delete record (soft delete if enabled)
     *
     * @param int $id Record ID
     * @return int Number of affected rows
     */
    public static function delete($id)
    {
        if (static::$softDelete) {
            return Database::softDelete(static::$table, static::$primaryKey . " = ?", [$id]);
        } else {
            return Database::delete(static::$table, static::$primaryKey . " = ?", [$id]);
        }
    }

    /**
     * Hard delete (permanently delete)
     *
     * @param int $id Record ID
     * @return int Number of affected rows
     */
    public static function forceDelete($id)
    {
        return Database::delete(static::$table, static::$primaryKey . " = ?", [$id]);
    }

    /**
     * Restore soft deleted record
     *
     * @param int $id Record ID
     * @return int Number of affected rows
     */
    public static function restore($id)
    {
        if (!static::$softDelete) {
            return 0;
        }

        return Database::update(
            static::$table,
            ['deleted_at' => null],
            static::$primaryKey . " = ?",
            [$id]
        );
    }

    /**
     * Count records
     *
     * @param array $conditions Conditions
     * @param bool $includeSoftDeleted Include soft deleted records
     * @return int
     */
    public static function count($conditions = [], $includeSoftDeleted = false)
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table;
        $params = [];

        $whereClauses = [];

        if (static::$softDelete && !$includeSoftDeleted) {
            $whereClauses[] = "deleted_at IS NULL";
        }

        foreach ($conditions as $field => $value) {
            $whereClauses[] = "{$field} = ?";
            $params[] = $value;
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Check if record exists
     *
     * @param int $id Record ID
     * @param bool $includeSoftDeleted Include soft deleted records
     * @return bool
     */
    public static function exists($id, $includeSoftDeleted = false)
    {
        return static::find($id, $includeSoftDeleted) !== false;
    }

    /**
     * Paginate results
     *
     * @param int $page Page number (1-based)
     * @param int $perPage Items per page
     * @param array $conditions Conditions
     * @param bool $includeSoftDeleted Include soft deleted records
     * @param string $orderBy ORDER BY clause
     * @return array ['data' => array, 'total' => int, 'page' => int, 'per_page' => int, 'total_pages' => int]
     */
    public static function paginate($page = 1, $perPage = 20, $conditions = [], $includeSoftDeleted = false, $orderBy = null)
    {
        $page = max(1, (int)$page);
        $perPage = min(100, max(1, (int)$perPage));
        $offset = ($page - 1) * $perPage;

        $data = static::all($conditions, $includeSoftDeleted, $orderBy, $perPage, $offset);
        $total = static::count($conditions, $includeSoftDeleted);
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'has_more' => $page < $totalPages
        ];
    }

    /**
     * Execute custom query
     *
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array
     */
    public static function query($sql, $params = [])
    {
        return Database::fetchAll($sql, $params);
    }

    /**
     * Execute custom query and return single result
     *
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false
     */
    public static function queryOne($sql, $params = [])
    {
        return Database::fetchOne($sql, $params);
    }
}
