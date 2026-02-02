<?php
/**
 * Auth Class
 *
 * Authentication and user management
 */

class Auth
{
    /**
     * Attempt login
     *
     * @param string $username Username
     * @param string $password Password
     * @return array ['success' => bool, 'message' => string, 'redirect' => string]
     */
    public static function attempt($username, $password)
    {
        // Get user by username
        $sql = "SELECT u.*, r.role_key, r.role_name
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                WHERE u.username = ?
                AND u.status = ?
                AND u.deleted_at IS NULL
                LIMIT 1";

        $user = Database::fetchOne($sql, [$username, USER_STATUS_ACTIVE]);

        if (!$user) {
            Logger::warning("Failed login attempt for username: {$username} (User not found or inactive)");
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }

        // Verify password
        if (!Security::verifyPassword($password, $user['password_hash'])) {
            Logger::warning("Failed login attempt (wrong password) for user ID: {$user['user_id']}");
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }

        // Create session
        self::login($user);

        // Log successful login
        Logger::logActivity($user['user_id'], 'login', 'User logged in successfully');

        return [
            'success' => true,
            'message' => 'Login successful',
            'redirect' => '/dashboard'
        ];
    }

    /**
     * Login user (set session data)
     *
     * @param array $user User data
     */
    public static function login($user)
    {
        Session::set(SESSION_USER_ID, $user['user_id']);
        Session::set(SESSION_USERNAME, $user['username']);
        Session::set(SESSION_ROLE_ID, $user['role_id']);
        Session::set(SESSION_ROLE_KEY, $user['role_key']);
        Session::set(SESSION_DEPARTMENT_ID, $user['department_id']);

        // Create session record in database
        try {
            Database::insert('sessions', [
                'session_id' => session_id(),
                'user_id' => $user['user_id'],
                'ip_address' => Security::getClientIp(),
                'user_agent' => Security::getUserAgent()
            ]);
        } catch (Exception $e) {
            // Log error but don't fail login
            Logger::error('Failed to create session record: ' . $e->getMessage());
        }
    }

    /**
     * Logout user
     */
    public static function logout()
    {
        $userId = self::id();

        if ($userId) {
            // Log logout
            Logger::logActivity($userId, 'logout', 'User logged out');

            // Remove session from database
            try {
                Database::delete('sessions', 'session_id = ?', [session_id()]);
            } catch (Exception $e) {
                Logger::error('Failed to delete session: ' . $e->getMessage());
            }
        }

        // Destroy session
        Session::destroy();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public static function check()
    {
        return Session::has(SESSION_USER_ID);
    }

    /**
     * Check if user is guest (not logged in)
     *
     * @return bool
     */
    public static function guest()
    {
        return !self::check();
    }

    /**
     * Get current user ID
     *
     * @return int|null
     */
    public static function id()
    {
        return Session::get(SESSION_USER_ID);
    }

    /**
     * Get current user data
     *
     * @return array|null
     */
    public static function user()
    {
        $userId = self::id();

        if (!$userId) {
            return null;
        }

        $sql = "SELECT u.*, r.role_key, r.role_name, d.department_name, d.department_code
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN departments d ON u.department_id = d.department_id
                WHERE u.user_id = ?
                AND u.deleted_at IS NULL
                LIMIT 1";

        return Database::fetchOne($sql, [$userId]);
    }

    /**
     * Get current username
     *
     * @return string|null
     */
    public static function username()
    {
        return Session::get(SESSION_USERNAME);
    }

    /**
     * Get current user's role key
     *
     * @return string|null
     */
    public static function roleKey()
    {
        return Session::get(SESSION_ROLE_KEY);
    }

    /**
     * Get current user's role ID
     *
     * @return int|null
     */
    public static function roleId()
    {
        return Session::get(SESSION_ROLE_ID);
    }

    /**
     * Get current user's department ID
     *
     * @return int|null
     */
    public static function departmentId()
    {
        return Session::get(SESSION_DEPARTMENT_ID);
    }

    /**
     * Check if user has specific role
     *
     * @param string|array $roles Role key(s)
     * @return bool
     */
    public static function hasRole($roles)
    {
        $userRole = self::roleKey();

        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }

        return $userRole === $roles;
    }

    /**
     * Check if user is requester
     *
     * @return bool
     */
    public static function isRequester()
    {
        return self::hasRole(ROLE_REQUESTER);
    }

    /**
     * Check if user is department supervisor
     *
     * @return bool
     */
    public static function isDeptSupervisor()
    {
        return self::hasRole(ROLE_DEPT_SUPERVISOR);
    }

    /**
     * Check if user is admin manager
     *
     * @return bool
     */
    public static function isAdminManager()
    {
        return self::hasRole(ROLE_ADMIN_MGR);
    }

    /**
     * Check if user is general admin manager
     *
     * @return bool
     */
    public static function isGeneralAdminManager()
    {
        return self::hasRole(ROLE_GENERAL_ADMIN_MGR);
    }

    /**
     * Check if user is stores officer
     *
     * @return bool
     */
    public static function isStoresOfficer()
    {
        return self::hasRole(ROLE_STORES_OFFICER);
    }

    /**
     * Require authentication (redirect if not logged in)
     *
     * @param string $redirectTo Redirect URL
     */
    public static function requireAuth($redirectTo = '/auth/login')
    {
        if (self::guest()) {
            Session::flash('error', 'Please login to continue');
            Response::redirect($redirectTo);
        }
    }

    /**
     * Require specific role (redirect if not authorized)
     *
     * @param string|array $roles Required role(s)
     * @param string $redirectTo Redirect URL
     */
    public static function requireRole($roles, $redirectTo = '/dashboard')
    {
        self::requireAuth();

        if (!self::hasRole($roles)) {
            Session::flash('error', 'You do not have permission to access this page');
            Response::redirect($redirectTo);
        }
    }

    /**
     * Change password
     *
     * @param int $userId User ID
     * @param string $newPassword New password
     * @return bool
     */
    public static function changePassword($userId, $newPassword)
    {
        // Validate password strength
        $validation = Security::validatePasswordStrength($newPassword);
        if (!$validation['valid']) {
            throw new Exception($validation['message']);
        }

        // Hash password
        $hash = Security::hashPassword($newPassword);

        // Update database
        Database::update('users', ['password_hash' => $hash], 'user_id = ?', [$userId]);

        Logger::logActivity($userId, 'password_change', 'User changed password');

        return true;
    }

    /**
     * Update last activity timestamp
     */
    public static function updateActivity()
    {
        if (self::check()) {
            try {
                Database::update(
                    'sessions',
                    ['last_activity' => date('Y-m-d H:i:s')],
                    'session_id = ?',
                    [session_id()]
                );
            } catch (Exception $e) {
                // Silently fail
            }
        }
    }
}
