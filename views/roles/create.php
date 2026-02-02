<div class="mb-6">
    <a href="<?= Security::url('/roles') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 block">&larr; Back to Roles</a>
    <h2 class="text-2xl font-bold text-gray-800">Create New Role</h2>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <form action="<?= Security::url('/roles/store') ?>" method="POST" class="p-6 space-y-4">
        <?= Security::csrfInput() ?>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
            <input type="text" name="role_name" required placeholder="e.g. Regional Manager"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role Key * (Unique Identifier)</label>
            <input type="text" name="role_key" required placeholder="e.g. regional_mgr"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <p class="mt-1 text-xs text-gray-500">Use lowercase and underscores, e.g. 'custom_role_name'</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" 
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition-colors">
                Create Role
            </button>
        </div>
    </form>
</div>
