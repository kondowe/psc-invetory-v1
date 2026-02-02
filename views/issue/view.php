<?php $pageTitle = 'Issue Voucher Details - ' . $voucher['issue_voucher_number']; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="<?= Security::url('/issue') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Vouchers
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Voucher #<?= $voucher['issue_voucher_number'] ?></h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Issued Items</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Issued</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <?php if (isset($item['is_custom']) && $item['is_custom']): ?>
                                    <div class="text-sm font-medium text-gray-900"><?= Security::e($item['custom_item_name']) ?></div>
                                    <div class="text-xs text-blue-600 font-bold uppercase tracking-wider">Custom Item</div>
                                <?php else: ?>
                                    <div class="text-sm font-medium text-gray-900"><?= Security::e($item['item_name']) ?></div>
                                    <div class="text-xs text-gray-500 font-mono"><?= Security::e($item['sku']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                <?= number_format($item['quantity_issued'], 2) ?> <?= Security::e($item['uom_code'] ?? 'Units') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php 
        // Fetch issued coupons if any
        $coupons = Database::fetchAll("SELECT c.* FROM fuel_coupons c WHERE c.issued_in_issue_voucher_id = ?", [$voucher['issue_voucher_id']]);
        if (!empty($coupons)): 
        ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <h3 class="text-lg font-semibold text-blue-800">Issued Fuel Coupons</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        <?php foreach ($coupons as $c): ?>
                            <div class="border rounded px-2 py-1 text-center bg-gray-50">
                                <span class="block text-[10px] font-bold text-gray-900"><?= Security::e($c['coupon_serial_number']) ?></span>
                                <span class="block text-[9px] text-gray-500"><?= number_format($c['coupon_value'], 0) ?> Ltrs</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($voucher['notes']): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notes</h3>
                <p class="text-gray-700"><?= nl2br(Security::e($voucher['notes'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Voucher Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Issue Date</p>
                    <p class="text-sm text-gray-900"><?= date('d M Y, H:i', strtotime($voucher['issue_date'])) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Request #</p>
                    <p class="text-sm text-blue-600 font-bold"><a href="<?= Security::url('/requests/view/' . $voucher['request_id']) ?>"><?= $voucher['request_number'] ?></a></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">From Store</p>
                    <p class="text-sm text-gray-900"><?= Security::e($voucher['store_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Issued By</p>
                    <p class="text-sm text-gray-900"><?= Security::e($voucher['issuer_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Received By</p>
                    <p class="text-sm text-gray-900 font-medium"><?= Security::e($voucher['received_by_name'] ?: 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>