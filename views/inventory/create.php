<?php $pageTitle = 'Add Inventory Item'; ?>

<div class="mb-6">
    <a href="<?= Security::url('/item') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Items
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Add Inventory Item</h2>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="<?= Security::url('/item/store') ?>" method="POST">
        <?= Security::csrfField() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU / Item Code *</label>
                <input type="text" name="sku" required value="<?= Security::e(Session::getOldInput('sku')) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                <input type="text" name="item_name" required value="<?= Security::e(Session::getOldInput('item_name')) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id'] ?>" <?= Session::getOldInput('category_id') == $category['category_id'] ? 'selected' : '' ?>>
                            <?= Security::e($category['display_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure *</label>
                <select name="uom_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select UOM</option>
                    <?php foreach ($uoms as $uom): ?>
                        <option value="<?= $uom['uom_id'] ?>" <?= Session::getOldInput('uom_id') == $uom['uom_id'] ? 'selected' : '' ?>>
                            <?= Security::e($uom['uom_name']) ?> (<?= Security::e($uom['uom_code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?= Security::e(Session::getOldInput('description')) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Level</label>
                <input type="number" step="0.01" name="minimum_stock_level" value="<?= Security::e(Session::getOldInput('minimum_stock_level', 0)) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                <input type="number" step="0.01" name="reorder_level" value="<?= Security::e(Session::getOldInput('reorder_level', 0)) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Standard Unit Cost</label>
                <input type="number" step="0.01" name="unit_cost" value="<?= Security::e(Session::getOldInput('unit_cost', 0)) ?>"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/item" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Create Item</button>
        </div>
    </form>
</div>
