<?php
/**
 * IssueVoucher Model
 */
require_once __DIR__ . '/BaseModel.php';

class IssueVoucher extends BaseModel
{
    protected static $table = 'issue_vouchers';
    protected static $primaryKey = 'issue_voucher_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'issue_voucher_number',
        'request_id',
        'store_id',
        'issued_by_user_id',
        'received_by_user_id',
        'received_by_name',
        'issue_date',
        'status',
        'notes',
        'created_at'
    ];

    public static function generateNumber()
    {
        $year = date('Y');
        $sql = "SELECT current_number FROM number_sequences WHERE sequence_name = 'issue' AND (last_reset_date IS NULL OR YEAR(last_reset_date) = ?)";
        $result = Database::fetchOne($sql, [$year]);
        
        if (!$result) {
            Database::query("INSERT INTO number_sequences (sequence_name, prefix, current_number, last_reset_date) VALUES ('issue', 'IV-', 1, CURRENT_DATE)");
            $num = 1;
        } else {
            $num = $result['current_number'] + 1;
            Database::query("UPDATE number_sequences SET current_number = ?, last_reset_date = CURRENT_DATE WHERE sequence_name = 'issue'", [$num]);
        }

        return 'IV-' . $year . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }
}
