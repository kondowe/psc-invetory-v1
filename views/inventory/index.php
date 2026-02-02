<?php $pageTitle = 'Inventory Items'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Inventory Items</h2>
    <?php if (RBAC::can('inventory.manage')): ?>
        <a href="<?= Security::url('/item/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add Item
        </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="<?= Security::url('/item') ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="<?= Security::e($filters['search'] ?? '') ?>" 
                   placeholder="SKU or Item Name" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="1" <?= (isset($filters['is_active']) && $filters['is_active'] === 1) ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= (isset($filters['is_active']) && $filters['is_active'] === 0) ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg mr-2">
                Filter
            </button>
            <a href="<?= Security::url('/item') ?>" class="text-gray-500 hover:text-gray-700 px-4 py-2">Clear</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UOM</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No items found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900"><?= Security::e($item['sku']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($item['item_name']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($item['category_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($item['uom_code']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                            $stockClass = 'text-gray-900';
                            $stockBadge = '';
                            if ($item['total_stock'] <= $item['minimum_stock_level']) {
                                $stockClass = 'text-red-600 font-bold';
                                $stockBadge = '<span class="ml-2 px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded">CRITICAL</span>';
                            } elseif ($item['total_stock'] <= $item['reorder_level']) {
                                $stockClass = 'text-yellow-600 font-bold';
                                $stockBadge = '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">REORDER</span>';
                            }
                            ?>
                            <span class="text-sm <?= $stockClass ?>">
                                <?= number_format($item['total_stock'], 2) ?>
                            </span>
                            <?= $stockBadge ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($item['is_active']): ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="<?= Security::url('/item/view/' . $item['item_id']) ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <?php if (RBAC::can('inventory.manage')): ?>
                                <a href="<?= Security::url('/item/edit/' . $item['item_id']) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <button onclick="deleteItem(<?= $item['item_id'] ?>, '<?= Security::e($item['item_name']) ?>')" 
                                        class="text-red-600 hover:text-red-900">Delete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-700">
            Showing page <?= $pagination['page'] ?> of <?= $pagination['total_pages'] ?> (<?= $pagination['total'] ?> items)
        </div>
        <div class="flex space-x-2">
            <?php if ($pagination['page'] > 1): ?>
                <a href="?page=<?= $pagination['page'] - 1 ?>&<?= http_build_query(array_filter($filters)) ?>" 
                   class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</a>
            <?php endif; ?>
            
            <?php if ($pagination['has_more']): ?>
                <a href="?page=<?= $pagination['page'] + 1 ?>&<?= http_build_query(array_filter($filters)) ?>" 
                   class="px-3 py-1 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteItem(itemId, itemName) {
    if (!confirm(`Are you sure you want to delete item "${itemName}"?`)) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`<?= Security::url('/item/delete/') ?>${itemId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to delete item'));
        }
    })
    .catch(error => {
        alert('Error deleting item: ' + error.message);
    });
}
</script>