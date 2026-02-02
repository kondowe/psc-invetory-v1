<?php $pageTitle = 'Supplier Details - ' . Security::e($supplier['supplier_name']); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="/supplier" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Suppliers
        </a>
        <h2 class="text-2xl font-bold text-gray-800"><?= Security::e($supplier['supplier_name']) ?></h2>
        <p class="text-sm text-gray-500 font-mono"><?= Security::e($supplier['supplier_code']) ?></p>
    </div>
    <?php if (RBAC::can('inventory.manage')): ?>
        <a href="/supplier/edit/<?= $supplier['supplier_id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Edit Supplier
        </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Contact Person</p>
                    <p class="text-sm text-gray-900"><?= Security::e($supplier['contact_person'] ?: 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-sm text-gray-900"><?= Security::e($supplier['email'] ?: 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Phone</p>
                    <p class="text-sm text-gray-900"><?= Security::e($supplier['phone'] ?: 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Address</p>
                    <p class="text-sm text-gray-900"><?= nl2br(Security::e($supplier['address'] ?: 'N/A')) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Type</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        <?= ucfirst(str_replace('_', ' ', $supplier['supplier_type'])) ?>
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $supplier['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $supplier['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Recent Goods Received Vouchers</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GRV #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($recentGrvs)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent GRVs found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentGrvs as $grv): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                    <a href="/grv/view/<?= $grv['grv_id'] ?>"><?= Security::e($grv['grv_number']) ?></a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y', strtotime($grv['received_date'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= Security::e($grv['store_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= number_format($grv['total_value'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                                        <?= $grv['status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($grv['status'] == 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $grv['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="px-6 py-3 bg-gray-50 border-t">
                <a href="/grv?supplier_id=<?= $supplier['supplier_id'] ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All GRVs for this supplier →</a>
            </div>
        </div>
    </div>
</div>
