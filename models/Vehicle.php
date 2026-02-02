<?php
/**
 * Vehicle Model
 */
require_once __DIR__ . '/BaseModel.php';

class Vehicle extends BaseModel
{
    protected static $table = 'vehicles';
    protected static $primaryKey = 'vehicle_id';
    protected static $fillable = [
        'vehicle_number',
        'vehicle_type',
        'fuel_type_id',
        'department_id',
        'status'
    ];
}
