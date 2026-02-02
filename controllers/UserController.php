<?php
/**
 * UserController
 */
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Department.php';

class UserController
{
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view');

        $users = Database::fetchAll("SELECT u.*, r.role_name, d.department_name 
                                    FROM users u 
                                    JOIN roles r ON u.role_id = r.role_id 
                                    LEFT JOIN departments d ON u.department_id = d.department_id 
                                    WHERE u.deleted_at IS NULL");

        Response::view('users/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        Auth::requireAuth();
        RBAC::require('user.create');

        $roles = Role::all();
        $departments = Department::all();

        Response::view('users/create', [
            'roles' => $roles,
            'departments' => $departments
        ]);
    }

    public function store()
    {
        Auth::requireAuth();
        RBAC::require('user.create');
        Security::checkCsrfToken();

        $data = [
            'username' => Security::sanitize($_POST['username'] ?? '', 'string'),
            'full_name' => Security::sanitize($_POST['full_name'] ?? '', 'string'),
            'email' => Security::sanitize($_POST['email'] ?? '', 'email'),
            'password' => $_POST['password'] ?? '',
            'role_id' => (int)($_POST['role_id'] ?? 0),
            'department_id' => !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null,
            'status' => 'active'
        ];

        // Validation
        if (empty($data['username']) || empty($data['full_name']) || empty($data['email']) || empty($data['password']) || empty($data['role_id'])) {
            Session::flash('error', 'All fields marked with * are required');
            Response::redirect('/users/create');
        }

        if (User::usernameExists($data['username'])) {
            Session::flash('error', 'Username already exists');
            Response::redirect('/users/create');
        }

        if (User::emailExists($data['email'])) {
            Session::flash('error', 'Email address already exists');
            Response::redirect('/users/create');
        }

        try {
            User::createUser($data);
            Logger::logActivity(Auth::id(), 'user_create', "Created new user: {$data['username']}");
            Session::flash('success', "User {$data['username']} created successfully");
            Response::redirect('/users');
        } catch (Exception $e) {
            Logger::error('User creation failed: ' . $e->getMessage());
            Session::flash('error', $e->getMessage());
            Response::redirect('/users/create');
        }
    }

    public function edit($userId)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');

        $user = User::find($userId);
        if (!$user) {
            Response::notFound();
        }

        $roles = Role::all();
        $departments = Department::all();

        Response::view('users/edit', [
            'user' => $user,
            'roles' => $roles,
            'departments' => $departments
        ]);
    }

    public function update($userId)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $data = [
            'username' => Security::sanitize($_POST['username'] ?? '', 'string'),
            'full_name' => Security::sanitize($_POST['full_name'] ?? '', 'string'),
            'email' => Security::sanitize($_POST['email'] ?? '', 'email'),
            'role_id' => (int)($_POST['role_id'] ?? 0),
            'department_id' => !empty($_POST['department_id']) ? (int)$_POST['department_id'] : null,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Validation
        if (empty($data['username']) || empty($data['full_name']) || empty($data['email']) || empty($data['role_id'])) {
            Session::flash('error', 'All fields marked with * are required');
            Response::redirect("/users/edit/$userId");
        }

        if (User::usernameExists($data['username'], $userId)) {
            Session::flash('error', 'Username already exists');
            Response::redirect("/users/edit/$userId");
        }

        if (User::emailExists($data['email'], $userId)) {
            Session::flash('error', 'Email address already exists');
            Response::redirect("/users/edit/$userId");
        }

        // Optional password update
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        try {
            User::updateUser($userId, $data);
            Logger::logActivity(Auth::id(), 'user_update', "Updated user: {$data['username']}");
            Session::flash('success', "User {$data['username']} updated successfully");
            Response::redirect('/users');
        } catch (Exception $e) {
            Logger::error('User update failed: ' . $e->getMessage());
            Session::flash('error', $e->getMessage());
            Response::redirect("/users/edit/$userId");
        }
    }

    /**
     * Reset user password to a new value
     */
    public function resetPassword($userId)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $newPassword = $_POST['new_password'] ?? '';
        
        if (strlen($newPassword) < 6) {
            Session::flash('error', 'Password must be at least 6 characters long');
            Response::redirect('/users');
        }

        try {
            $user = User::find($userId);
            if (!$user) {
                Response::notFound();
            }

            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            User::update($userId, ['password_hash' => $hash]);

            Logger::logActivity(Auth::id(), 'password_reset', "Reset password for user: {$user['username']}");
            
            Session::flash('success', "Password for {$user['full_name']} has been reset successfully.");
            Response::redirect('/users');

        } catch (Exception $e) {
            Logger::error('Password reset failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to reset password.');
            Response::redirect('/users');
        }
    }
}
