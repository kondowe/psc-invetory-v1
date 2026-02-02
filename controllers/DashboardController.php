<?php
/**
 * DashboardController
 *
 * Handles role-specific dashboards
 */

class DashboardController
{
    /**
     * Show dashboard (role-based)
     */
    public function index()
    {
        Auth::requireAuth();

        $user = Auth::user();

        // Route based on permissions, not just hardcoded role keys
        if (RBAC::can('dashboard.general_admin')) {
            $this->generalAdminDashboard();
        } elseif (RBAC::can('dashboard.admin_manager')) {
            $this->adminManagerDashboard();
        } elseif (RBAC::can('dashboard.stores_officer')) {
            $this->storesOfficerDashboard();
        } elseif (RBAC::can('dashboard.supervisor')) {
            $this->supervisorDashboard();
        } elseif (RBAC::can('dashboard.requester')) {
            $this->requesterDashboard();
        } else {
            Response::view('dashboard/default', ['user' => $user]);
        }
    }

    /**
     * Requester Dashboard
     */
    private function requesterDashboard()
    {
        $userId = Auth::id();

        // Get my requests count by status
        $sql = "SELECT status, COUNT(*) as count
                FROM requests
                WHERE requester_user_id = ?
                AND deleted_at IS NULL
                GROUP BY status";

        $statusCounts = Database::fetchAll($sql, [$userId]);

        // Get recent requests
        $recentRequests = Database::fetchAll(
            "SELECT * FROM requests
             WHERE requester_user_id = ?
             AND deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT 5",
            [$userId]
        );

        Response::view('dashboard/requester', [
            'statusCounts' => $statusCounts,
            'recentRequests' => $recentRequests
        ]);
    }

    /**
     * Department Supervisor Dashboard
     */
    private function supervisorDashboard()
    {
        $departmentId = Auth::departmentId();

        // Get pending approvals count
        $pendingApprovals = 0; // TODO: Implement workflow logic

        Response::view('dashboard/supervisor', [
            'pendingApprovals' => $pendingApprovals
        ]);
    }

    /**
     * Administration Manager Dashboard
     */
    private function adminManagerDashboard()
    {
        // Overview Stats
        $stats = [
            'pending_count' => Database::fetchOne("SELECT COUNT(*) as count FROM requests WHERE status = 'pending' AND deleted_at IS NULL")['count'],
            'approved_today' => Database::fetchOne("SELECT COUNT(*) as count FROM requests WHERE status = 'approved' AND DATE(updated_at) = CURDATE()")['count'],
            'total_value' => 0 // Placeholder if value tracking is added later
        ];

        // Priority Distribution
        $priorityData = Database::fetchAll("SELECT priority, COUNT(*) as count FROM requests WHERE status = 'pending' AND deleted_at IS NULL GROUP BY priority");

        // Department Distribution
        $deptData = Database::fetchAll("SELECT d.department_name, COUNT(r.request_id) as count 
                                       FROM departments d 
                                       LEFT JOIN requests r ON d.department_id = r.department_id 
                                       WHERE r.deleted_at IS NULL
                                       GROUP BY d.department_id");

        $lowStockAlerts = StockLevel::getLowStockAlerts();

        Response::view('dashboard/admin_manager', [
            'stats' => $stats,
            'priorityData' => $priorityData,
            'deptData' => $deptData,
            'lowStockAlerts' => $lowStockAlerts
        ]);
    }

    /**
     * General Administration Manager Dashboard
     */
    private function generalAdminDashboard()
    {
        // System-wide stats
        $stats = [
            'total_requests' => Database::fetchOne("SELECT COUNT(*) as count FROM requests WHERE deleted_at IS NULL")['count'],
            'pending_requests' => Database::fetchOne("SELECT COUNT(*) as count FROM requests WHERE status = 'pending' AND deleted_at IS NULL")['count'],
            'total_users' => Database::fetchOne("SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL")['count'],
            'total_items' => Database::fetchOne("SELECT COUNT(*) as count FROM items WHERE deleted_at IS NULL")['count']
        ];

        // Requests by Status
        $statusSql = "SELECT status, COUNT(*) as count FROM requests WHERE deleted_at IS NULL GROUP BY status";
        $statusData = Database::fetchAll($statusSql);

        // Requests by Month (Last 6 months)
        $trendSql = "SELECT DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count 
                     FROM requests 
                     WHERE deleted_at IS NULL 
                     AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                     GROUP BY month 
                     ORDER BY created_at ASC";
        $trendData = Database::fetchAll($trendSql);

        $lowStockAlerts = StockLevel::getLowStockAlerts();

        Response::view('dashboard/general_admin', [
            'stats' => $stats,
            'statusData' => $statusData,
            'trendData' => $trendData,
            'lowStockAlerts' => $lowStockAlerts
        ]);
    }

    /**
     * Stores Officer Dashboard
     */
    private function storesOfficerDashboard()
    {
        // Get pending releases
        $pendingReleases = Database::fetchOne(
            "SELECT COUNT(*) as count FROM requests
             WHERE status = 'approved' AND deleted_at IS NULL"
        )['count'];

        $lowStockAlerts = StockLevel::getLowStockAlerts();

        Response::view('dashboard/stores_officer', [
            'pendingReleases' => $pendingReleases,
            'lowStockAlerts' => $lowStockAlerts
        ]);
    }
}
