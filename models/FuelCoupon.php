<?php
/**
 * FuelCoupon Model
 */
require_once __DIR__ . '/BaseModel.php';

class FuelCoupon extends BaseModel
{
    protected static $table = 'fuel_coupons';
    protected static $primaryKey = 'coupon_id';
    protected static $fillable = [
        'coupon_serial_number',
        'item_id',
        'fuel_type_id',
        'coupon_value',
        'value_type',
        'expiry_date',
        'status',
        'grv_id',
        'issued_in_issue_voucher_id',
        'issued_date'
    ];
}
