<?php $pageTitle = 'Item Categories'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Item Categories</h2>
    <?php if (RBAC::can('inventory.manage')): ?>
        <a href="/categories/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add Category
        </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($categoriesWithCounts)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No categories found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categoriesWithCounts as $category): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900"><?= Security::e($category['category_code']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900"><?= Security::e($category['category_name']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if ($category['parent_category_id']): ?>
                                <?php
                                    $parent = array_filter($categoriesWithCounts, function($c) use ($category) {
                                        return $c['category_id'] == $category['parent_category_id'];
                                    });
                                    $parent = reset($parent);
                                    echo Security::e($parent['category_name'] ?? 'Unknown');
                                ?>
                            <?php else: ?>
                                <span class="text-gray-400">Root</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= $category['item_count'] ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($category['is_fuel_category']): ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Fuel
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    General
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <?php if (RBAC::can('inventory.manage')): ?>
                                <a href="/categories/edit/<?= $category['category_id'] ?>" class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </a>
                                <?php if ($category['item_count'] == 0): ?>
                                    <button onclick="deleteCategory(<?= $category['category_id'] ?>, '<?= Security::e($category['category_name']) ?>')"
                                            class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (RBAC::can('inventory.manage')): ?>
<script>
function deleteCategory(categoryId, categoryName) {
    if (!confirm(`Are you sure you want to delete category "${categoryName}"?`)) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/categories/delete/${categoryId}`, {
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
            alert('Error: ' + (data.message || 'Failed to delete category'));
        }
    })
    .catch(error => {
        alert('Error deleting category: ' + error.message);
    });
}
</script>
<?php endif; ?>
