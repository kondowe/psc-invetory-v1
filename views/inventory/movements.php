<?php $pageTitle = 'Stock Movements'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Stock Movements</h2>
    <div class="flex space-x-2">
        <a href="/item/stockLevels" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            Back to Balances
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="/item/movements" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
            <select name="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Items</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?= $item['item_id'] ?>" <?= (isset($filters['item_id']) && $filters['item_id'] == $item['item_id']) ? 'selected' : '' ?>>
                        <?= Security::e($item['item_name']) ?> (<?= Security::e($item['sku']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Store</label>
            <select name="store_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Stores</option>
                <?php foreach ($stores as $store): ?>
                    <option value="<?= $store['store_id'] ?>" <?= (isset($filters['store_id']) && $filters['store_id'] == $store['store_id']) ? 'selected' : '' ?>>
                        <?= Security::e($store['store_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Types</option>
                <option value="grv_in" <?= (isset($filters['type']) && $filters['type'] == 'grv_in') ? 'selected' : '' ?>>GRV In</option>
                <option value="issue_out" <?= (isset($filters['type']) && $filters['type'] == 'issue_out') ? 'selected' : '' ?>>Issue Out</option>
                <option value="adjustment" <?= (isset($filters['type']) && $filters['type'] == 'adjustment') ? 'selected' : '' ?>>Adjustment</option>
                <option value="transfer_in" <?= (isset($filters['type']) && $filters['type'] == 'transfer_in') ? 'selected' : '' ?>>Transfer In</option>
                <option value="transfer_out" <?= (isset($filters['type']) && $filters['type'] == 'transfer_out') ? 'selected' : '' ?>>Transfer Out</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2">
                Filter
            </button>
            <a href="/item/movements" class="text-gray-500 hover:text-gray-700 px-4 py-2">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($movements)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No movements found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($movements as $m): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            <?= date('d M Y H:i', strtotime($m['movement_date'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($m['item_name']) ?></div>
                            <div class="text-xs text-gray-500 font-mono"><?= Security::e($m['sku']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= Security::e($m['store_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= strpos($m['movement_type'], 'in') !== false || $m['movement_type'] == 'adjustment' && $m['quantity'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= strtoupper(str_replace('_', ' ', $m['movement_type'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $m['quantity'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= ($m['quantity'] > 0 ? '+' : '') . number_format($m['quantity'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="text-xs">Before: <?= number_format($m['balance_before'], 2) ?></span><br>
                            <span class="font-bold text-gray-900">After: <?= number_format($m['balance_after'], 2) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($m['reference_type']) ?> #<?= $m['reference_id'] ?><br>
                            <span class="text-xs italic"><?= Security::e($m['notes']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
