<?php
/**
 * StockMovement Model
 *
 * Records all inventory transactions (in/out/adjustments)
 */

require_once __DIR__ . '/BaseModel.php';

class StockMovement extends BaseModel
{
    protected static $table = 'stock_movements';
    protected static $primaryKey = 'movement_id';
    protected static $fillable = [
        'item_id',
        'store_id',
        'movement_type',
        'quantity',
        'reference_type',
        'reference_id',
        'balance_before',
        'balance_after',
        'performed_by_user_id',
        'notes'
    ];
    protected static $softDelete = false;

    /**
     * Record a new stock movement
     *
     * @param array $data Movement data
     * @return int Movement ID
     */
    public static function record($data)
    {
        // Get balance before from StockLevel
        $sql = "SELECT quantity_on_hand FROM stock_levels WHERE item_id = ? AND store_id = ?";
        $stock = Database::fetchOne($sql, [$data['item_id'], $data['store_id']]);
        
        $balanceBefore = $stock ? $stock['quantity_on_hand'] : 0;
        $balanceAfter = $balanceBefore + $data['quantity'];

        $data['balance_before'] = $balanceBefore;
        $data['balance_after'] = $balanceAfter;
        $data['performed_by_user_id'] = Auth::id();
        $data['movement_date'] = date('Y-m-d H:i:s');

        // Start transaction
        Database::beginTransaction();
        try {
            // 1. Create movement record
            $movementId = static::create($data);

            // 2. Update StockLevel
            require_once __DIR__ . '/StockLevel.php';
            StockLevel::updateBalance($data['item_id'], $data['store_id'], $data['quantity']);

            Database::commit();
            return $movementId;
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }

    /**
     * Get movements for an item
     *
     * @param int $itemId Item ID
     * @param int|null $storeId Filter by store
     * @return array
     */
    public static function getForItem($itemId, $storeId = null)
    {
        $sql = "SELECT m.*, s.store_name, u.full_name as performer_name
                FROM " . static::$table . " m
                JOIN stores s ON m.store_id = s.store_id
                JOIN users u ON m.performed_by_user_id = u.user_id
                WHERE m.item_id = ?";
        $params = [$itemId];

        if ($storeId) {
            $sql .= " AND m.store_id = ?";
            $params[] = $storeId;
        }

        $sql .= " ORDER BY m.movement_date DESC";
        return Database::fetchAll($sql, $params);
    }
}
