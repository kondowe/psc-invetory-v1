<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="<?= Security::url('/roles') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 block">&larr; Back to Roles</a>
        <h2 class="text-2xl font-bold text-gray-800">Manage Permissions: <?= Security::e($role['role_name']) ?></h2>
    </div>
</div>

<form action="<?= Security::url('/roles/update/' . $role['role_id']) ?>" method="POST" class="space-y-6">
    <?= Security::csrfInput() ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($allPermissions as $module => $permissions): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-700 uppercase tracking-wider text-xs"><?= Security::e(ucfirst($module)) ?> Module</h3>
                </div>
                <div class="p-4 space-y-3">
                    <?php foreach ($permissions as $p): ?>
                        <label class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="permissions[]" value="<?= $p['permission_id'] ?>" 
                                    <?= in_array($p['permission_id'], $rolePermissionIds) ? 'checked' : '' ?>
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-700"><?= Security::e($p['description']) ?></span>
                                <p class="text-gray-500 text-xs"><?= Security::e($p['permission_key']) ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-end pt-6 border-t border-gray-200">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition-colors">
            Save Permissions
        </button>
    </div>
</form>
