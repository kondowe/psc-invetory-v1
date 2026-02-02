<?php $pageTitle = 'Suppliers'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Suppliers</h2>
    <?php if (RBAC::can('inventory.manage')): ?>
        <a href="<?= Security::url('/supplier/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add Supplier
        </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($suppliers)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No suppliers found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900"><?= Security::e($supplier['supplier_code']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($supplier['supplier_name']) ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($supplier['contact_person']) ?><br>
                            <span class="text-xs"><?= Security::e($supplier['email']) ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                <?= ucfirst(str_replace('_', ' ', $supplier['supplier_type'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if ($supplier['is_active']): ?>
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
                            <a href="<?= Security::url('/supplier/view/' . $supplier['supplier_id']) ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                            <?php if (RBAC::can('inventory.manage')): ?>
                                <a href="<?= Security::url('/supplier/edit/' . $supplier['supplier_id']) ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
                                <button onclick="deleteSupplier(<?= $supplier['supplier_id'] ?>, '<?= Security::e($supplier['supplier_name']) ?>')" 
                                        class="text-red-600 hover:text-red-900">Delete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function deleteSupplier(supplierId, supplierName) {
    if (!confirm(`Are you sure you want to delete supplier "${supplierName}"?`)) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`<?= Security::url('/supplier/delete/') ?>${supplierId}`, {
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
            alert('Error: ' + (data.message || 'Failed to delete supplier'));
        }
    })
    .catch(error => {
        alert('Error deleting supplier: ' + error.message);
    });
}
</script>
