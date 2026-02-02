<?php
$user = Auth::user();
$roleKey = $user['role_key'];

// Helper to determine active link
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = rtrim(dirname($scriptName), '/\\');
$relativeReqPath = str_replace($baseDir, '', $currentPath);
$relativeReqPath = '/' . trim($relativeReqPath, '/');

function isActive($path, $current) {
    if ($path === $current) return true;
    
    // For details/edit pages (e.g. /requests/view/1 should highlight /requests)
    // but only if there isn't a more specific match like /requests/own
    $exactMatchPaths = [
        '/requests/own', 
        '/requests/create', 
        '/requests/pending', 
        '/grv/create', 
        '/issue/pending',
        '/item/movements',
        '/item/stockLevels'
    ];
    if (in_array($current, $exactMatchPaths)) {
        return $path === $current;
    }

    if ($path !== '/' && strpos($current, $path) === 0) {
        // Only return true if the next character is a / or it's the end of string
        $pathLen = strlen($path);
        if (strlen($current) == $pathLen || $current[$pathLen] == '/') {
            return true;
        }
    }
    return false;
}

function getLinkClass($path, $current) {
    $base = "flex items-center px-4 py-3 rounded-lg mb-1 transition-colors ";
    if (isActive($path, $current)) {
        return $base . "bg-blue-600 text-white font-bold shadow-md";
    }
    return $base . "text-gray-300 hover:bg-gray-800";
}
?>
<div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="bg-gray-900 text-white w-64 flex-shrink-0 overflow-y-auto fixed lg:relative inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-30">
    <!-- Logo & Close Button -->
    <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <img src="<?= Security::url('/assets/images/logo.png') ?>" alt="Logo" class="h-10 w-10">
            <div>
                <h2 class="text-xl font-bold">IMS</h2>
                <p class="text-xs text-gray-400">Inventory Management</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-6">
        <!-- Dashboard -->
        <a href="<?= Security::url('/dashboard') ?>" class="<?= getLinkClass('/dashboard', $relativeReqPath) ?>">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- Requester Menu -->
        <?php if (RBAC::canAny(['request.create', 'request.view_own', 'request.view_department', 'request.view_all'])): ?>
        <div class="mb-4 mt-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Requests</p>
            <?php if (RBAC::can('request.create')): ?>
            <a href="<?= Security::url('/requests/create') ?>" class="<?= getLinkClass('/requests/create', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Request
            </a>
            <?php endif; ?>
            
            <?php if (RBAC::can('request.view_own')): ?>
            <a href="<?= Security::url('/requests/own') ?>" class="<?= getLinkClass('/requests/own', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                My Requests
            </a>
            <?php endif; ?>

            <?php if (RBAC::canAny(['request.view_department', 'request.view_all'])): ?>
            <a href="<?= Security::url('/requests') ?>" class="<?= getLinkClass('/requests', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <?= RBAC::can('request.view_all') ? 'All Requests' : 'Dept Requests' ?>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Approvals & Workflow -->
        <?php if (RBAC::canAny(['request.approve', 'workflow.configure', 'workflow.view'])): ?>
        <div class="mb-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approvals</p>
            <?php if (RBAC::can('request.approve')): ?>
            <a href="<?= Security::url('/requests/pending') ?>" class="<?= getLinkClass('/requests/pending', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pending Approvals
            </a>
            <?php endif; ?>
            
            <?php if (RBAC::can('workflow.configure')): ?>
            <a href="<?= Security::url('/workflow/configure') ?>" class="<?= getLinkClass('/workflow', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Workflow Setup
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Inventory -->
        <?php if (RBAC::canAny(['inventory.view', 'inventory.manage'])): ?>
        <div class="mb-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Inventory</p>
            <a href="<?= Security::url('/item') ?>" class="<?= getLinkClass('/item', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Items
            </a>
            <a href="<?= Security::url('/categories') ?>" class="<?= getLinkClass('/categories', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Categories
            </a>
            <a href="<?= Security::url('/supplier') ?>" class="<?= getLinkClass('/supplier', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Suppliers
            </a>
        </div>
        <?php endif; ?>

        <!-- Procurement & Stock -->
        <?php if (RBAC::canAny(['grv.view', 'grv.create', 'issue.view', 'issue.create'])): ?>
        <div class="mb-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Procurement & Stock</p>
            <?php if (RBAC::canAny(['grv.view', 'grv.create'])): ?>
            <a href="<?= Security::url('/grv') ?>" class="<?= getLinkClass('/grv', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                GRV (Receiving)
            </a>
            <?php endif; ?>
            
            <a href="<?= Security::url('/item/stockLevels') ?>" class="<?= getLinkClass('/item/stockLevels', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Stock Balances
            </a>

            <?php if (RBAC::canAny(['issue.view', 'issue.create'])): ?>
            <a href="<?= Security::url('/issue/pending') ?>" class="<?= getLinkClass('/issue/pending', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Pending Releases
            </a>
            <a href="<?= Security::url('/issue') ?>" class="<?= getLinkClass('/issue', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Issue Vouchers
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Reports -->
        <?php if (RBAC::canAny(['report.view_own', 'report.view_department', 'report.view_all'])): ?>
        <div class="mb-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reports</p>
            <a href="<?= Security::url('/reports') ?>" class="<?= getLinkClass('/reports', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Reports
            </a>
        </div>
        <?php endif; ?>

        <!-- System Administration -->
        <?php if (RBAC::canAny(['user.view', 'user.create', 'user.edit'])): ?>
        <div class="mb-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">System</p>
            <a href="<?= Security::url('/users') ?>" class="<?= getLinkClass('/users', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Users
            </a>
            <a href="<?= Security::url('/departments') ?>" class="<?= getLinkClass('/departments', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Departments
            </a>
            <a href="<?= Security::url('/roles') ?>" class="<?= getLinkClass('/roles', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 2.944V21m0-18.056L3.382 6m8.618-3.056L20.618 6M12 21a11.955 11.955 0 01-8.618-3.04M12 21a11.955 11.955 0 008.618-3.04M3.382 6a11.955 11.955 0 008.618 12M20.618 6a11.955 11.955 0 01-8.618 12"></path>
                </svg>
                Role Management
            </a>
            <a href="<?= Security::url('/audit') ?>" class="<?= getLinkClass('/audit', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m3 4L10 14l-3-3"></path>
                </svg>
                Audit Trail
            </a>
            <a href="<?= Security::url('/sla') ?>" class="<?= getLinkClass('/sla', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                SLA Management
            </a>
            <a href="<?= Security::url('/request-management') ?>" class="<?= getLinkClass('/request-management', $relativeReqPath) ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 4h.01M9 16h.01"></path>
                </svg>
                Request Management
            </a>
        </div>
        <?php endif; ?>
    </nav>
</aside>