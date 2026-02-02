<?php $pageTitle = 'Item Details - ' . Security::e($item['item_name']); ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="/item" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Items
        </a>
        <h2 class="text-2xl font-bold text-gray-800"><?= Security::e($item['item_name']) ?></h2>
        <p class="text-sm text-gray-500 font-mono"><?= Security::e($item['sku']) ?></p>
    </div>
    <?php if (RBAC::can('inventory.manage')): ?>
        <a href="/item/edit/<?= $item['item_id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Edit Item
        </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Category</p>
                    <p class="text-sm text-gray-900"><?= Security::e($item['category_name']) ?> (<?= Security::e($item['category_code']) ?>)</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Unit of Measure</p>
                    <p class="text-sm text-gray-900"><?= Security::e($item['uom_name']) ?> (<?= Security::e($item['uom_code']) ?>)</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Standard Cost</p>
                    <p class="text-sm text-gray-900"><?= number_format($item['unit_cost'], 2) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full <?= $item['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Description</p>
                    <p class="text-sm text-gray-900"><?= nl2br(Security::e($item['description'] ?: 'No description provided.')) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Stock Levels by Store</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Store</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Hand</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($stockLevels)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No stock levels found for this item</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stockLevels as $stock): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= Security::e($stock['store_name'] ?? 'Unknown Store') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= number_format($stock['quantity_on_hand'] ?? 0, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($stock['quantity_reserved'] ?? 0, 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">
                                        <?= number_format($stock['quantity_available'] ?? 0, 2) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Inventory Control</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Minimum Stock Level</p>
                    <p class="text-lg font-bold text-gray-900"><?= number_format($item['minimum_stock_level'], 2) ?></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Reorder Level</p>
                    <p class="text-lg font-bold text-blue-600"><?= number_format($item['reorder_level'], 2) ?></p>
                </div>
                <?php 
                $totalOnHand = array_sum(array_column($stockLevels, 'quantity_on_hand'));
                $totalAvailable = array_sum(array_column($stockLevels, 'quantity_available'));
                ?>
                <div class="pt-4 border-t">
                    <p class="text-sm font-medium text-gray-500">Total Stock (All Stores)</p>
                    <p class="text-2xl font-bold <?= $totalOnHand <= $item['minimum_stock_level'] ? 'text-red-600' : 'text-green-600' ?>">
                        <?= number_format($totalOnHand, 2) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Recent Movements</h3>
            <p class="text-sm text-gray-500 text-center py-4 italic">No recent movements found</p>
            <div class="mt-4">
                <a href="/stock/movements?item_id=<?= $item['item_id'] ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View Full History →</a>
            </div>
        </div>
    </div>
</div>
