<?php
/**
 * RoleController
 */
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Permission.php';

class RoleController
{
    /**
     * List all roles
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view'); 

        $roles = Role::getAllWithUserCount();

        Response::view('roles/index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show create role form
     */
    public function create()
    {
        Auth::requireAuth();
        RBAC::require('user.edit');

        Response::view('roles/create');
    }

    /**
     * Store a new role
     */
    public function store()
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $data = [
            'role_name' => Security::sanitize($_POST['role_name'] ?? '', 'string'),
            'role_key' => Security::sanitize($_POST['role_key'] ?? '', 'string'),
            'description' => Security::sanitize($_POST['description'] ?? '', 'string')
        ];

        if (empty($data['role_name']) || empty($data['role_key'])) {
            Session::flash('error', 'Role name and key are required');
            Response::redirect(Security::url('/roles/create'));
        }

        // Check if key already exists
        if (Role::findByKey($data['role_key'])) {
            Session::flash('error', 'Role key already exists');
            Response::redirect(Security::url('/roles/create'));
        }

        if (Role::createRole($data)) {
            Logger::logActivity(Auth::id(), 'role_create', "Created new role: {$data['role_name']}");
            Session::flash('success', 'Role created successfully');
            Response::redirect(Security::url('/roles'));
        } else {
            Session::flash('error', 'Failed to create role');
            Response::redirect(Security::url('/roles/create'));
        }
    }

    /**
     * Edit role permissions
     */
    public function edit($roleId)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');

        $role = Role::find($roleId);
        if (!$role) {
            Response::notFound();
        }

        $allPermissions = Permission::getAllGroupedByModule();
        $rolePermissions = Role::getPermissions($roleId);
        $rolePermissionIds = array_column($rolePermissions, 'permission_id');

        Response::view('roles/edit', [
            'role' => $role,
            'allPermissions' => $allPermissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }

    /**
     * Update role permissions
     */
    public function update($roleId)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $permissionIds = $_POST['permissions'] ?? [];
        
        if (Role::syncPermissions($roleId, $permissionIds)) {
            Logger::logActivity(Auth::id(), 'role_update', "Updated permissions for role: {$roleId}");
            Session::flash('success', 'Permissions updated successfully');
        } else {
            Session::flash('error', 'Failed to update permissions');
        }

        Response::redirect(Security::url('/roles'));
    }
}
