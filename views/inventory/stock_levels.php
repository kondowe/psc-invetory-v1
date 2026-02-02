<?php $pageTitle = 'Stock Balances'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Stock Balances</h2>
    <div class="flex space-x-2">
        <a href="/item/movements" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            View Movements
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="/item/stockLevels" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="<?= Security::e($filters['search'] ?? '') ?>" 
                   placeholder="SKU or Item Name" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_id'] ?>" <?= (isset($filters['category_id']) && $filters['category_id'] == $category['category_id']) ? 'selected' : '' ?>>
                        <?= Security::e($category['display_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2">
                Filter
            </button>
            <a href="/item/stockLevels" class="text-gray-500 hover:text-gray-700 px-4 py-2">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Hand</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($stocks)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No stock records found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($stock['item_name']) ?></div>
                            <div class="text-xs text-gray-500 font-mono"><?= Security::e($stock['sku']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($stock['category_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= Security::e($stock['store_name'] ?: 'N/A') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= number_format($stock['quantity_on_hand'] ?? 0, 2) ?> <?= Security::e($stock['uom_code']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= number_format($stock['quantity_reserved'] ?? 0, 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold <?= ($stock['quantity_available'] ?? 0) <= $stock['minimum_stock_level'] ? 'text-red-600' : 'text-green-600' ?>">
                                <?= number_format($stock['quantity_available'] ?? 0, 2) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if (($stock['quantity_available'] ?? 0) <= $stock['minimum_stock_level']): ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Low Stock
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Normal
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
