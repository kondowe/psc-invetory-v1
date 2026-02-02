<?php
/**
 * StockLevel Model
 *
 * Manages stock balances across different stores
 */

require_once __DIR__ . '/BaseModel.php';

class StockLevel extends BaseModel
{
    protected static $table = 'stock_levels';
    protected static $primaryKey = 'stock_id';
    protected static $fillable = [
        'item_id',
        'store_id',
        'quantity_on_hand',
        'quantity_reserved'
    ];
    protected static $softDelete = false;

    /**
     * Get stock level for a specific item in a specific store
     *
     * @param int $itemId Item ID
     * @param int $storeId Store ID
     * @return array|false
     */
    public static function getBalance($itemId, $storeId)
    {
        return static::first(['item_id' => $itemId, 'store_id' => $storeId]);
    }

    /**
     * Update stock balance
     *
     * @param int $itemId Item ID
     * @param int $storeId Store ID
     * @param float $quantity Quantity to add (positive) or subtract (negative)
     * @return bool
     */
    public static function updateBalance($itemId, $storeId, $quantity)
    {
        $stock = static::getBalance($itemId, $storeId);

        if ($stock) {
            $newQuantity = $stock['quantity_on_hand'] + $quantity;
            return static::update($stock['stock_id'], [
                'quantity_on_hand' => $newQuantity
            ]);
        } else {
            // Create new record if it doesn't exist
            if ($quantity < 0) {
                throw new Exception("Cannot subtract stock from non-existent record");
            }
            return static::create([
                'item_id' => $itemId,
                'store_id' => $storeId,
                'quantity_on_hand' => $quantity,
                'quantity_reserved' => 0
            ]);
        }
    }

    /**
     * Reserve stock for an approved request
     *
     * @param int $itemId Item ID
     * @param int $storeId Store ID
     * @param float $quantity Quantity to reserve
     * @return bool
     */
    public static function reserveStock($itemId, $storeId, $quantity)
    {
        $stock = static::getBalance($itemId, $storeId);

        if (!$stock || $stock['quantity_available'] < $quantity) {
            throw new Exception("Insufficient stock available to reserve");
        }

        return static::update($stock['stock_id'], [
            'quantity_reserved' => $stock['quantity_reserved'] + $quantity
        ]);
    }

    /**
     * Release reserved stock (when issued or cancelled)
     *
     * @param int $itemId Item ID
     * @param int $storeId Store ID
     * @param float $quantity Quantity to release
     * @return bool
     */
    public static function releaseReserved($itemId, $storeId, $quantity)
    {
        $stock = static::getBalance($itemId, $storeId);

        if (!$stock) {
            return false;
        }

        $newReserved = max(0, $stock['quantity_reserved'] - $quantity);
        return static::update($stock['stock_id'], [
            'quantity_reserved' => $newReserved
        ]);
    }

    /**
     * Get items that have reached minimum or reorder levels
     *
     * @return array
     */
    public static function getLowStockAlerts()
    {
        $sql = "SELECT sl.*, i.item_name, i.sku, i.minimum_stock_level, i.reorder_level, s.store_name,
                       CASE 
                           WHEN sl.quantity_available <= i.minimum_stock_level THEN 'critical'
                           WHEN sl.quantity_available <= i.reorder_level THEN 'reorder'
                           ELSE 'normal'
                       END as alert_level
                FROM stock_levels sl
                INNER JOIN items i ON sl.item_id = i.item_id
                INNER JOIN stores s ON sl.store_id = s.store_id
                WHERE (sl.quantity_available <= i.minimum_stock_level OR sl.quantity_available <= i.reorder_level)
                AND i.deleted_at IS NULL
                ORDER BY alert_level ASC, sl.quantity_available ASC";

        return Database::fetchAll($sql);
    }
}
