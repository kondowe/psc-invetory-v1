<?php $pageTitle = 'Create Issue Voucher'; ?>

<div class="mb-6">
    <a href="<?= Security::url('/issue/pending') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Pending
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Issue Items for Request #<?= $request['request_number'] ?></h2>
</div>

<form action="<?= Security::url('/issue/store') ?>" method="POST">
    <?= Security::csrfInput() ?>
    <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
    <input type="hidden" name="request_number" value="<?= $request['request_number'] ?>">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Issuance Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store / Warehouse *</label>
                        <select name="store_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <?php foreach ($stores as $s): ?>
                                <option value="<?= $s['store_id'] ?>"><?= Security::e($s['store_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Received By (Name)</label>
                        <input type="text" name="received_by_name" value="<?= Security::e($request['requester_name']) ?>"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Items to Issue</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Approved</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Already Issued</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Qty to Issue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($items as $item): ?>
                            <?php 
                                $remaining = $item['quantity_approved'] - $item['quantity_issued']; 
                                if ($remaining <= 0) continue;
                            ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <?php if ($item['is_custom']): ?>
                                        <div class="text-sm font-medium text-gray-900"><?= Security::e($item['custom_item_name']) ?></div>
                                        <div class="text-xs text-blue-600 font-bold uppercase tracking-wider">Custom Item</div>
                                    <?php else: ?>
                                        <div class="text-sm font-medium text-gray-900"><?= Security::e($item['item_name']) ?></div>
                                        <div class="text-xs text-gray-500 font-mono"><?= Security::e($item['sku']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900"><?= number_format($item['quantity_approved'], 2) ?></td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500"><?= number_format($item['quantity_issued'], 2) ?></td>
                                <td class="px-6 py-4">
                                    <input type="number" step="0.01" max="<?= $remaining ?>" 
                                           name="items[<?= $item['request_item_id'] ?>][quantity]" 
                                           id="qty_<?= $item['request_item_id'] ?>"
                                           value="<?= $remaining ?>"
                                           <?= $request['request_type'] === 'fuel' ? 'readonly class="bg-gray-100"' : '' ?>
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($request['request_type'] === 'fuel'): ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b bg-blue-50 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-blue-800">Select Individual Coupons</h3>
                        <div class="text-sm font-bold text-blue-600">
                            Total Selected: <span id="selectedLiters">0.00</span> / <?= number_format($remaining, 2) ?> Ltrs
                        </div>
                    </div>
                    <div class="p-6">
                        <?php if (empty($availableCoupons)): ?>
                            <p class="text-red-500 italic text-sm text-center">No available coupons found for this fuel type!</p>
                        <?php else: ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-96 overflow-y-auto p-1">
                                <?php foreach ($availableCoupons as $coupon): ?>
                                    <label class="relative flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="selected_coupons[]" value="<?= $coupon['coupon_id'] ?>" 
                                                   data-value="<?= $coupon['coupon_value'] ?>"
                                                   onchange="calculateSelectedCoupons()"
                                                   class="coupon-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        </div>
                                        <div class="ml-3 text-xs">
                                            <span class="font-bold text-gray-900 block"><?= Security::e($coupon['coupon_serial_number']) ?></span>
                                            <span class="text-gray-500"><?= number_format($coupon['coupon_value'], 0) ?> Ltrs</span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                function calculateSelectedCoupons() {
                    let total = 0;
                    document.querySelectorAll('.coupon-checkbox:checked').forEach(cb => {
                        total += parseFloat(cb.dataset.value);
                    });
                    document.getElementById('selectedLiters').textContent = total.toFixed(2);
                    
                    // Update the hidden/readonly quantity input for the fuel item
                    // We assume there's only one fuel item in a fuel request
                    const qtyInput = document.querySelector('input[name^="items"][name$="[quantity]"]');
                    if (qtyInput) {
                        qtyInput.value = total.toFixed(2);
                    }
                }
                // Initial calculation
                window.addEventListener('DOMContentLoaded', calculateSelectedCoupons);
                </script>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Finalize</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Optional..."></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow">
                    Confirm Issuance
                </button>
            </div>
        </div>
    </div>
</form>
