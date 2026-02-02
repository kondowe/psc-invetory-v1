<?php $pageTitle = 'User Management'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Users</h2>
    <a href="<?= Security::url('/users/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Add User</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users as $u): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= Security::e($u['full_name']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($u['username']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($u['role_name']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($u['department_name'] ?: 'N/A') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><?= ucfirst($u['status']) ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                        <a href="<?= Security::url('/users/edit/' . $u['user_id']) ?>" class="text-blue-600 hover:text-blue-900 font-bold">Edit</a>
                        <button onclick="openResetModal(<?= $u['user_id'] ?>, '<?= Security::e($u['full_name']) ?>')" class="text-orange-600 hover:text-orange-900 font-bold">Reset Password</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Reset Password Modal -->
<div id="resetModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Reset Password</h3>
            <p class="text-sm text-gray-500 mt-1" id="userNameText"></p>
            <form id="resetForm" method="POST" class="mt-4">
                <?= Security::csrfField() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Min 6 characters">
                </div>
                <div class="mt-5 flex justify-end space-x-2">
                    <button type="button" onclick="closeResetModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-bold hover:bg-orange-700">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openResetModal(userId, fullName) {
    const modal = document.getElementById('resetModal');
    const form = document.getElementById('resetForm');
    const userText = document.getElementById('userNameText');
    
    form.action = `<?= Security::url('/users/resetPassword/') ?>${userId}`;
    userText.textContent = `User: ${fullName}`;
    
    modal.classList.remove('hidden');
}

function closeResetModal() {
    document.getElementById('resetModal').classList.add('hidden');
}
</script>