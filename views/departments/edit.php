<div class="mb-6">
    <a href="<?= Security::url('/departments') ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-2 block">&larr; Back to Departments</a>
    <h2 class="text-2xl font-bold text-gray-800">Edit Department: <?= Security::e($dept['department_name']) ?></h2>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <form action="<?= Security::url('/departments/update/' . $dept['department_id']) ?>" method="POST" class="p-6 space-y-4">
        <?= Security::csrfInput() ?>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
            <input type="text" name="department_name" required value="<?= Security::e($dept['department_name']) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Department Code *</label>
            <input type="text" name="department_code" required value="<?= Security::e($dept['department_code']) ?>"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supervisor</label>
            <select name="supervisor_user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">No Supervisor</option>
                <?php foreach ($supervisors as $s): ?>
                    <option value="<?= $s['user_id'] ?>" <?= $dept['supervisor_user_id'] == $s['user_id'] ? 'selected' : '' ?>>
                        <?= Security::e($s['full_name']) ?> (<?= Security::e($s['username']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="active" <?= $dept['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $dept['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>
