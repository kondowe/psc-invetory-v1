<?php $pageTitle = 'Edit Supplier'; ?>

<div class="mb-6">
    <a href="/supplier" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Suppliers
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Edit Supplier: <?= Security::e($supplier['supplier_name']) ?></h2>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="/supplier/update/<?= $supplier['supplier_id'] ?>" method="POST">
        <?= Security::csrfInput() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Code *</label>
                <input type="text" name="supplier_code" required value="<?= Security::e($supplier['supplier_code']) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name *</label>
                <input type="text" name="supplier_name" required value="<?= Security::e($supplier['supplier_name']) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                <input type="text" name="contact_person" value="<?= Security::e($supplier['contact_person']) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Type</label>
                <select name="supplier_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="general" <?= $supplier['supplier_type'] == 'general' ? 'selected' : '' ?>>General Supplier</option>
                    <option value="fuel_vendor" <?= $supplier['supplier_type'] == 'fuel_vendor' ? 'selected' : '' ?>>Fuel Vendor</option>
                    <option value="both" <?= $supplier['supplier_type'] == 'both' ? 'selected' : '' ?>>Both</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="<?= Security::e($supplier['email']) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="<?= Security::e($supplier['phone']) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= Security::e($supplier['address']) ?></textarea>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" <?= $supplier['is_active'] ? 'checked' : '' ?> class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/supplier" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Update Supplier</button>
        </div>
    </form>
</div>
