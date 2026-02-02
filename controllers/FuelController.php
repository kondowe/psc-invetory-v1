<?php
/**
 * FuelController
 */
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/FuelCoupon.php';
require_once __DIR__ . '/../models/Department.php';

class FuelController
{
    public function index()
    {
        Auth::requireAuth();
        
        $sql = "SELECT c.*, i.item_name, f.fuel_type_name
                FROM fuel_coupons c
                JOIN items i ON c.item_id = i.item_id
                JOIN fuel_types f ON c.fuel_type_id = f.fuel_type_id
                ORDER BY c.created_at DESC LIMIT 100";
        $coupons = Database::fetchAll($sql);

        Response::view('fuel/index', [
            'coupons' => $coupons
        ]);
    }

    public function vehicles()
    {
        Auth::requireAuth();
        
        $sql = "SELECT v.*, d.department_name, f.fuel_type_name
                FROM vehicles v
                LEFT JOIN departments d ON v.department_id = d.department_id
                LEFT JOIN fuel_types f ON v.fuel_type_id = f.fuel_type_id
                WHERE v.deleted_at IS NULL";
        $vehicles = Database::fetchAll($sql);

        Response::view('fuel/vehicles', [
            'vehicles' => $vehicles
        ]);
    }

    public function createVehicle()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');

        $departments = Department::all();
        $fuelTypes = Database::fetchAll("SELECT * FROM fuel_types");

        Response::view('fuel/vehicle_create', [
            'departments' => $departments,
            'fuelTypes' => $fuelTypes
        ]);
    }

    public function storeVehicle()
    {
        Auth::requireAuth();
        RBAC::require('inventory.manage');
        Security::checkCsrfToken();

        Vehicle::create([
            'vehicle_number' => Security::sanitize($_POST['vehicle_number'], 'string'),
            'vehicle_type' => Security::sanitize($_POST['vehicle_type'], 'string'),
            'fuel_type_id' => (int)$_POST['fuel_type_id'],
            'department_id' => (int)$_POST['department_id'],
            'status' => 'active'
        ]);

        Session::flash('success', 'Vehicle registered successfully');
        Response::redirect('/fuel/vehicles');
    }
}
