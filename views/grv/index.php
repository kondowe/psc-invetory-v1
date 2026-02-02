<?php $pageTitle = 'Goods Received Vouchers'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Goods Received Vouchers</h2>
    <?php if (RBAC::can('grv.create')): ?>
        <a href="<?= Security::url('/grv/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Create GRV
        </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow mb-6">
    <div class="border-b px-6 py-4 flex space-x-4">
        <a href="<?= Security::url('/grv') ?>" class="px-3 py-1 rounded-full text-sm <?= !$currentStatus ? 'bg-blue-100 text-blue-800 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">All</a>
        <a href="<?= Security::url('/grv?status=draft') ?>" class="px-3 py-1 rounded-full text-sm <?= $currentStatus == 'draft' ? 'bg-blue-100 text-blue-800 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">Draft</a>
        <a href="<?= Security::url('/grv?status=pending_approval') ?>" class="px-3 py-1 rounded-full text-sm <?= $currentStatus == 'pending_approval' ? 'bg-blue-100 text-blue-800 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">Pending</a>
        <a href="<?= Security::url('/grv?status=approved') ?>" class="px-3 py-1 rounded-full text-sm <?= $currentStatus == 'approved' ? 'bg-blue-100 text-blue-800 font-bold' : 'text-gray-500 hover:bg-gray-100' ?>">Approved</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GRV #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($grvs)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No vouchers found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($grvs as $grv): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= Security::e($grv['grv_number']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d M Y', strtotime($grv['received_date'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= Security::e($grv['supplier_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= Security::e($grv['store_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= number_format($grv['total_value'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?= $grv['status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                                       ($grv['status'] == 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($grv['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $grv['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= Security::url('/grv/view/' . $grv['grv_id']) ?>" class="text-blue-600 hover:text-blue-900">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
