<?php $pageTitle = 'GRV Details - ' . Security::e($grv['grv_number']); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="<?= Security::url('/grv') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to GRVs
        </a>
        <h2 class="text-2xl font-bold text-gray-800">GRV #<?= Security::e($grv['grv_number']) ?></h2>
    </div>
    <div class="flex space-x-2">
        <?php if ($grv['status'] == 'draft'): ?>
            <form action="<?= Security::url('/grv/cancel/' . $grv['grv_id']) ?>" method="POST" onsubmit="return confirm('Cancel this GRV?')">
                <?= Security::csrfInput() ?>
                <button type="submit" class="bg-white border border-red-300 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50">Cancel</button>
            </form>
            <form action="<?= Security::url('/grv/store') ?>" method="POST">
                 <?= Security::csrfInput() ?>
                 <!-- This is simplified, usually we'd have a separate edit/update for GRV -->
            </form>
        <?php endif; ?>
        
        <?php if ($grv['status'] == 'pending_approval' && RBAC::can('grv.approve')): ?>
            <form action="<?= Security::url('/grv/approve/' . $grv['grv_id']) ?>" method="POST" onsubmit="return confirm('Approve this GRV and update stock?')">
                <?= Security::csrfInput() ?>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-bold">Approve & Receive</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Items Received</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch/Expiry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= Security::e($item['item_name']) ?></div>
                                <div class="text-xs text-gray-500 font-mono mb-1"><?= Security::e($item['sku']) ?></div>
                                <?php if ($item['notes']): ?>
                                    <div class="text-xs text-blue-600 italic"><?= Security::e($item['notes']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                <?php if ($item['batch_number']): ?>
                                    <div>Batch: <?= Security::e($item['batch_number']) ?></div>
                                <?php endif; ?>
                                <?php if ($item['expiry_date']): ?>
                                    <div class="<?= strtotime($item['expiry_date']) < time() ? 'text-red-600 font-bold' : '' ?>">
                                        Exp: <?= date('d M Y', strtotime($item['expiry_date'])) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$item['batch_number'] && !$item['expiry_date']): ?>
                                    <span class="text-gray-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= number_format($item['quantity'], 2) ?> <?= Security::e($item['uom_code']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= number_format($item['unit_cost'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                <?= number_format($item['quantity'] * $item['unit_cost'], 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm text-gray-500 uppercase">Total Value</td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg text-blue-600">
                            <?= number_format($grv['total_value'], 2) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if ($grv['notes']): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Notes</h3>
                <p class="text-gray-700"><?= nl2br(Security::e($grv['notes'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Status & Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Status</p>
                    <span class="px-2 py-1 text-sm font-bold rounded-full 
                        <?= $grv['status'] == 'approved' ? 'bg-green-100 text-green-800' : 
                           ($grv['status'] == 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                        <?= strtoupper(str_replace('_', ' ', $grv['status'])) ?>
                    </span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Supplier</p>
                    <p class="text-sm text-gray-900 font-medium"><?= Security::e($grv['supplier_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= Security::e($grv['supplier_code']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Store</p>
                    <p class="text-sm text-gray-900 font-medium"><?= Security::e($grv['store_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Received Date</p>
                    <p class="text-sm text-gray-900"><?= date('d M Y', strtotime($grv['received_date'])) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Reference</p>
                    <p class="text-sm text-gray-900"><?= Security::e(ucfirst(str_replace('_', ' ', $grv['reference_type']))) ?>: <?= Security::e($grv['reference_number'] ?: 'N/A') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Audit Trail</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-2 w-2 mt-1.5 rounded-full bg-blue-500"></div>
                    <div class="ml-3">
                        <p class="text-xs font-medium text-gray-900">Received by</p>
                        <p class="text-xs text-gray-500"><?= Security::e($grv['receiver_name']) ?></p>
                        <p class="text-[10px] text-gray-400"><?= date('d M Y H:i', strtotime($grv['created_at'])) ?></p>
                    </div>
                </div>
                <?php if ($grv['approved_by_user_id']): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-2 w-2 mt-1.5 rounded-full bg-green-500"></div>
                        <div class="ml-3">
                            <p class="text-xs font-medium text-gray-900">Approved by</p>
                            <p class="text-xs text-gray-500"><?= Security::e($grv['approver_name']) ?></p>
                            <p class="text-[10px] text-gray-400"><?= date('d M Y H:i', strtotime($grv['approved_date'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
