<?php
/**
 * AuditController
 */
require_once __DIR__ . '/../models/ActivityLog.php';
require_once __DIR__ . '/../models/AuditLog.php';

class AuditController
{
    /**
     * Display activity logs
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view'); // Assuming admin-level access

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $activities = ActivityLog::getAllWithUsers($perPage, $offset);
        
        // Count total for pagination
        $total = Database::fetchOne("SELECT COUNT(*) as count FROM activity_logs")['count'];
        $totalPages = ceil($total / $perPage);

        Response::view('audit/index', [
            'activities' => $activities,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'type' => 'activity'
        ]);
    }

    /**
     * Display data audit logs
     */
    public function data()
    {
        Auth::requireAuth();
        RBAC::require('user.view');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $audits = AuditLog::getAllWithUsers($perPage, $offset);
        
        $total = Database::fetchOne("SELECT COUNT(*) as count FROM audit_logs")['count'];
        $totalPages = ceil($total / $perPage);

        Response::view('audit/data', [
            'audits' => $audits,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'type' => 'audit'
        ]);
    }
}
