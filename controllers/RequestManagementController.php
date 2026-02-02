<?php
/**
 * RequestManagementController
 */
require_once __DIR__ . '/../models/SystemConfig.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Item.php';

class RequestManagementController
{
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view'); // Admin only

        $roles = Role::all();
        
        $settings = [
            'enforce' => SystemConfig::get('enforce_stock_restrictions', true),
            'below_reorder' => SystemConfig::get('roles_can_request_below_reorder', []),
            'below_min' => SystemConfig::get('roles_can_request_below_min', [])
        ];

        Response::view('request_management/index', [
            'roles' => $roles,
            'settings' => $settings
        ]);
    }

    public function updateSettings()
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $enforce = isset($_POST['enforce_stock_restrictions']) ? 'true' : 'false';
        $belowReorder = $_POST['roles_below_reorder'] ?? [];
        $belowMin = $_POST['roles_below_min'] ?? [];

        SystemConfig::set('enforce_stock_restrictions', $enforce);
        SystemConfig::set('roles_can_request_below_reorder', $belowReorder);
        SystemConfig::set('roles_can_request_below_min', $belowMin);

        Logger::logActivity(Auth::id(), 'settings_update', "Updated request restriction rules");
        Session::flash('success', 'Request management settings updated');
        Response::redirect(Security::url('/request-management'));
    }
}
