<?php
/**
 * Request Model
 */
require_once __DIR__ . '/BaseModel.php';

class Request extends BaseModel
{
    protected static $table = 'requests';
    protected static $primaryKey = 'request_id';
    protected static $fillable = [
        'request_number',
        'request_type',
        'requester_user_id',
        'department_id',
        'purpose',
        'priority',
        'status',
        'date_required',
        'current_workflow_step_id',
        'workflow_instance_id',
        'vehicle_id',
        'departure_point',
        'destination_point',
        'is_round_trip',
        'departure_date',
        'request_company_vehicle',
        'fuel_type_id',
        'submitted_at',
        'closed_at',
        'cancelled_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function generateNumber()
    {
        $year = date('Y');
        $sql = "SELECT current_number FROM number_sequences WHERE sequence_name = 'request' AND (last_reset_date IS NULL OR YEAR(last_reset_date) = ?)";
        $result = Database::fetchOne($sql, [$year]);
        
        if (!$result) {
            Database::query("INSERT INTO number_sequences (sequence_name, prefix, current_number, last_reset_date) VALUES ('request', 'REQ-', 1, CURRENT_DATE)");
            $num = 1;
        } else {
            $num = $result['current_number'] + 1;
            Database::query("UPDATE number_sequences SET current_number = ?, last_reset_date = CURRENT_DATE WHERE sequence_name = 'request'", [$num]);
        }

        return 'REQ-' . $year . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }

    public static function getWithDetails($requestId)
    {
        $sql = "SELECT r.*, u.full_name as requester_name, d.department_name, v.vehicle_number
                FROM " . static::$table . " r
                JOIN users u ON r.requester_user_id = u.user_id
                JOIN departments d ON r.department_id = d.department_id
                LEFT JOIN vehicles v ON r.vehicle_id = v.vehicle_id
                WHERE r.request_id = ?";
        return Database::fetchOne($sql, [$requestId]);
    }
}
