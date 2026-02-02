<?php $pageTitle = 'Edit User'; ?>

<div class="mb-6">
    <a href="<?= Security::url('/users') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Users
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Edit User: <?= Security::e($user['full_name']) ?></h2>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <form action="<?= Security::url('/users/update/' . $user['user_id']) ?>" method="POST" class="p-6 space-y-6">
        <?= Security::csrfInput() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                <input type="text" name="username" required value="<?= Security::e($user['username']) ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" name="full_name" required value="<?= Security::e($user['full_name']) ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" name="email" required value="<?= Security::e($user['email']) ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Leave blank to keep current">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select name="role_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['role_id'] ?>" <?= $user['role_id'] == $role['role_id'] ? 'selected' : '' ?>><?= Security::e($role['role_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">None / General</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= $dept['department_id'] ?>" <?= $user['department_id'] == $dept['department_id'] ? 'selected' : '' ?>><?= Security::e($dept['department_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="suspended" <?= $user['status'] == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t flex justify-end space-x-3">
            <a href="<?= Security::url('/users') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-medium">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold">
                Update User
            </button>
        </div>
    </form>
</div>
