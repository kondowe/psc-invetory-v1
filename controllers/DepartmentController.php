<?php
/**
 * DepartmentController
 */
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/User.php';

class DepartmentController
{
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view'); 

        $departments = Department::getAllWithSupervisors();

        Response::view('departments/index', [
            'departments' => $departments
        ]);
    }

    /**
     * Edit department
     */
    public function edit($id)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');

        $department = Department::find($id);
        if (!$department) Response::notFound();

        // Get potential supervisors
        $supervisors = User::all(['deleted_at' => null]); 

        Response::view('departments/edit', [
            'dept' => $department,
            'supervisors' => $supervisors
        ]);
    }

    /**
     * Update department
     */
    public function update($id)
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $data = [
            'department_name' => Security::sanitize($_POST['department_name'] ?? '', 'string'),
            'department_code' => Security::sanitize($_POST['department_code'] ?? '', 'string'),
            'supervisor_user_id' => !empty($_POST['supervisor_user_id']) ? (int)$_POST['supervisor_user_id'] : null,
            'status' => $_POST['status'] ?? 'active'
        ];

        if (empty($data['department_name']) || empty($data['department_code'])) {
            Session::flash('error', 'Department name and code are required');
            Response::back();
        }

        try {
            Department::update($id, $data);
            Logger::logActivity(Auth::id(), 'department_update', "Updated department: {$data['department_name']}");
            Session::flash('success', 'Department updated successfully');
            Response::redirect(Security::url('/departments'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to update department: ' . $e->getMessage());
            Response::back();
        }
    }
}
