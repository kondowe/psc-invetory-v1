<?php $pageTitle = 'Pending Issuances'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Approved Requests Awaiting Issuance</h2>
    <p class="text-gray-600 text-sm">Select an approved request to create an issue voucher.</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Req #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No pending issuances found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($requests as $r): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $r['request_number'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($r['requester_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($r['department_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <a href="<?= Security::url('/requests/view/' . $r['request_id']) ?>" class="text-blue-600 hover:text-blue-900">View Request</a>
                            <a href="<?= Security::url('/issue/create/' . $r['request_id']) ?>" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Issue Items</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
