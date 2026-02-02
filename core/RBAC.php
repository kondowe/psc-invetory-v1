<?php
/**
 * RBAC Class
 *
 * Role-Based Access Control - Permission checking
 */

class RBAC
{
    private static $permissions = [];
    private static $loaded = false;

    /**
     * Load permissions for current user
     */
    private static function loadPermissions()
    {
        if (self::$loaded) {
            return;
        }

        $roleId = Auth::roleId();

        if (!$roleId) {
            self::$loaded = true;
            return;
        }

        $sql = "SELECT p.permission_key
                FROM permissions p
                INNER JOIN role_permissions rp ON p.permission_id = rp.permission_id
                WHERE rp.role_id = ?";

        $result = Database::fetchAll($sql, [$roleId]);

        self::$permissions = array_column($result, 'permission_key');
        self::$loaded = true;
    }

    /**
     * Check if user has permission
     *
     * @param string $permissionKey Permission key (e.g., 'request.create')
     * @return bool
     */
    public static function can($permissionKey)
    {
        if (!Auth::check()) {
            return false;
        }

        self::loadPermissions();

        return in_array($permissionKey, self::$permissions);
    }

    /**
     * Check if user has any of the given permissions
     *
     * @param array $permissions Array of permission keys
     * @return bool
     */
    public static function canAny($permissions)
    {
        foreach ($permissions as $permission) {
            if (self::can($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     *
     * @param array $permissions Array of permission keys
     * @return bool
     */
    public static function canAll($permissions)
    {
        foreach ($permissions as $permission) {
            if (!self::can($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Require permission (throw exception if not authorized)
     *
     * @param string $permissionKey Permission key
     * @throws Exception
     */
    public static function require($permissionKey)
    {
        if (!self::can($permissionKey)) {
            Logger::warning('Unauthorized access attempt: ' . $permissionKey . ' by user: ' . Auth::id());
            throw new Exception('You do not have permission to perform this action');
        }
    }

    /**
     * Check if user can view own resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canViewOwn($module)
    {
        return self::can("{$module}.view_own");
    }

    /**
     * Check if user can view department resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canViewDepartment($module)
    {
        return self::can("{$module}.view_department");
    }

    /**
     * Check if user can view all resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canViewAll($module)
    {
        return self::can("{$module}.view_all");
    }

    /**
     * Check if user can create resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canCreate($module)
    {
        return self::can("{$module}.create");
    }

    /**
     * Check if user can edit resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canEdit($module)
    {
        return self::can("{$module}.edit");
    }

    /**
     * Check if user can delete resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canDelete($module)
    {
        return self::can("{$module}.delete");
    }

    /**
     * Check if user can approve resources
     *
     * @param string $module Module name
     * @return bool
     */
    public static function canApprove($module)
    {
        return self::can("{$module}.approve");
    }

    /**
     * Check if user is owner of resource
     *
     * @param int $resourceUserId Resource owner user ID
     * @return bool
     */
    public static function isOwner($resourceUserId)
    {
        return Auth::id() === (int)$resourceUserId;
    }

    /**
     * Check if user is in same department as resource
     *
     * @param int $resourceDepartmentId Resource department ID
     * @return bool
     */
    public static function isSameDepartment($resourceDepartmentId)
    {
        return Auth::departmentId() === (int)$resourceDepartmentId;
    }

    /**
     * Check if user can access resource based on ownership and permissions
     *
     * @param string $module Module name
     * @param int|null $resourceUserId Resource owner user ID
     * @param int|null $resourceDepartmentId Resource department ID
     * @return bool
     */
    public static function canAccessResource($module, $resourceUserId = null, $resourceDepartmentId = null)
    {
        // Can view all
        if (self::canViewAll($module)) {
            return true;
        }

        // Can view department and same department
        if ($resourceDepartmentId !== null && self::canViewDepartment($module) && self::isSameDepartment($resourceDepartmentId)) {
            return true;
        }

        // Can view own and is owner
        if ($resourceUserId !== null && self::canViewOwn($module) && self::isOwner($resourceUserId)) {
            return true;
        }

        return false;
    }

    /**
     * Get all permissions for current user
     *
     * @return array
     */
    public static function getPermissions()
    {
        self::loadPermissions();
        return self::$permissions;
    }

    /**
     * Clear cached permissions (useful after role change)
     */
    public static function clearCache()
    {
        self::$permissions = [];
        self::$loaded = false;
    }

    /**
     * Check if user is department supervisor for specific department
     *
     * @param int|null $departmentId Department ID (null for any department)
     * @return bool
     */
    public static function isDeptSupervisor($departmentId = null)
    {
        if (!Auth::isDeptSupervisor()) {
            return false;
        }

        if ($departmentId === null) {
            return true;
        }

        return Auth::departmentId() === (int)$departmentId;
    }

    /**
     * Middleware-style permission check with redirect
     *
     * @param string $permissionKey Permission key
     * @param string $redirectTo Redirect URL if unauthorized
     */
    public static function middleware($permissionKey, $redirectTo = '/dashboard')
    {
        if (!self::can($permissionKey)) {
            Session::flash('error', 'You do not have permission to access this page');
            Response::redirect($redirectTo);
        }
    }
}
