<?php $pageTitle = 'Supervisor Dashboard'; $user = Auth::user(); ?>
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Department Supervisor Dashboard</h2>
    <p class="text-gray-600 mt-1">Manage department requests and workflows</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <p class="text-gray-700">Pending approvals: <?= $pendingApprovals ?? 0 ?></p>
</div>
