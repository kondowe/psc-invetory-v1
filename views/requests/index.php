<?php 
$isTabbed = $isTabbed ?? false;
if ($isOwnOnly) {
    $pageTitle = 'My Requests';
} else {
    $pageTitle = RBAC::can('request.view_all') ? 'All Requests' : 'Department Requests';
}
?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800"><?= $pageTitle ?></h2>
    <?php if (RBAC::can('request.create')): ?>
        <a href="<?= Security::url('/requests/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            New Request
        </a>
    <?php endif; ?>
</div>

<?php if ($isTabbed): ?>
    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchRequestTab('needs_approval')" id="needsApprovalTab" class="req-tab-btn border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Needs My Approval (<?= count($needsApproval) ?>)
            </button>
            <button onclick="switchRequestTab('pending')" id="pendingTab" class="req-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Pending Higher Approval (<?= count($pendingOther) ?>)
            </button>
            <button onclick="switchRequestTab('approved')" id="approvedTab" class="req-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Approved/Issued (<?= count($approved) ?>)
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div id="needs_approval_content" class="req-tab-content">
        <?php renderRequestTable($needsApproval); ?>
    </div>
    <div id="pending_content" class="req-tab-content hidden">
        <?php renderRequestTable($pendingOther); ?>
    </div>
    <div id="approved_content" class="req-tab-content hidden">
        <?php renderRequestTable($approved); ?>
    </div>

    <script>
    function switchRequestTab(tabId) {
        document.querySelectorAll('.req-tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(tabId + '_content').classList.remove('hidden');
        
        document.querySelectorAll('.req-tab-btn').forEach(b => {
            b.classList.remove('border-blue-500', 'text-blue-600');
            b.classList.add('border-transparent', 'text-gray-500');
        });
        
        const activeTab = document.getElementById(tabId + 'Tab');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
        activeTab.classList.add('border-blue-500', 'text-blue-600');
    }
    </script>

<?php else: ?>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <?php renderRequestTable($requests); ?>
    </div>
<?php endif; ?>

<?php
/**
 * Helper to render the request table
 */
function renderRequestTable($requestList) {
    ?>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Req #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($requestList)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No requests found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requestList as $r): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $r['request_number'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($r['requester_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= ucfirst($r['request_type']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $r['priority'] == 'urgent' ? 'bg-red-100 text-red-800' : 
                                       ($r['priority'] == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') ?>">
                                    <?= ucfirst($r['priority']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $r['status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($r['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($r['status'] == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= Security::url('/requests/view/' . $r['request_id']) ?>" class="text-blue-600 hover:text-blue-900">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>