<?php
/**
 * GoodsReceivedVoucher Model
 *
 * Manages the header of a goods receipt
 */

require_once __DIR__ . '/BaseModel.php';

class GoodsReceivedVoucher extends BaseModel
{
    protected static $table = 'goods_received_vouchers';
    protected static $primaryKey = 'grv_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'grv_number',
        'supplier_id',
        'store_id',
        'reference_number',
        'reference_type',
        'received_date',
        'received_by_user_id',
        'approved_by_user_id',
        'approved_date',
        'status',
        'total_value',
        'notes',
        'created_at',
        'updated_at'
    ];

    /**
     * Get GRV with related data
     *
     * @param int $grvId GRV ID
     * @return array|false
     */
    public static function getWithDetails($grvId)
    {
        $sql = "SELECT grv.*, s.supplier_name, s.supplier_code, st.store_name, 
                       u1.full_name as receiver_name, u2.full_name as approver_name
                FROM " . static::$table . " grv
                JOIN suppliers s ON grv.supplier_id = s.supplier_id
                JOIN stores st ON grv.store_id = st.store_id
                JOIN users u1 ON grv.received_by_user_id = u1.user_id
                LEFT JOIN users u2 ON grv.approved_by_user_id = u2.user_id
                WHERE grv.grv_id = ?";
        
        return Database::fetchOne($sql, [$grvId]);
    }

    /**
     * Generate next GRV number
     *
     * @return string
     */
    public static function generateNumber()
    {
        $year = date('Y');
        $sql = "SELECT current_number FROM number_sequences WHERE sequence_name = 'grv' AND (last_reset_date IS NULL OR YEAR(last_reset_date) = ?)";
        $result = Database::fetchOne($sql, [$year]);
        
        if (!$result) {
            // Initialize sequence if not exists
            Database::query("INSERT INTO number_sequences (sequence_name, prefix, current_number, last_reset_date) VALUES ('grv', 'GRV-', 1, CURRENT_DATE)");
            $num = 1;
        } else {
            $num = $result['current_number'] + 1;
            Database::query("UPDATE number_sequences SET current_number = ?, last_reset_date = CURRENT_DATE WHERE sequence_name = 'grv'", [$num]);
        }

        return 'GRV-' . $year . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
