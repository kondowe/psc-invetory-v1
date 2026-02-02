<?php $pageTitle = 'Workflow Steps - ' . $template['template_name']; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="<?= Security::url('/workflow/configure' . ($template['department_id'] ? '?dept_id=' . $template['department_id'] : '')) ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Configuration
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Manage Steps: <?= Security::e($template['template_name']) ?></h2>
    </div>
    <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm">
        + Add Step
    </button>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Order</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Step Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approver Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conditions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($steps as $step): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900"><?= $step['step_order'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= Security::e($step['step_name']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php 
                            $role = array_filter($roles, function($r) use ($step) { return $r['role_id'] == $step['approver_role_id']; });
                            $role = reset($role);
                            echo Security::e($role['role_name'] ?? 'Unknown');
                        ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 italic">
                        <?= $step['condition_type'] == 'none' ? 'Always runs' : 'Conditional' ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <?php if (!$step['is_system_step'] || Auth::isGeneralAdminManager()): ?>
                            <form action="<?= Security::url('/workflow/deleteStep/' . $step['workflow_step_id']) ?>" method="POST" class="inline" onsubmit="return confirm('Remove this step?')">
                                <?= Security::csrfInput() ?>
                                <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400 italic text-xs">Locked (System)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Step Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Add Workflow Step</h3>
            <form action="<?= Security::url('/workflow/addStep/' . $template['workflow_template_id']) ?>" method="POST" class="space-y-4">
                <?= Security::csrfInput() ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Step Order *</label>
                    <input type="number" name="step_order" value="<?= count($steps) + 1 ?>" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Step Name *</label>
                    <input type="text" name="step_name" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Supervisor Review">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Approver Role *</label>
                    <select name="approver_role_id" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['role_id'] ?>"><?= Security::e($role['role_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_mandatory" id="is_mandatory" checked class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-gray-300">
                    <label for="is_mandatory" class="ml-2 block text-sm text-gray-900">Mandatory Step</label>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Add Step</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('addModal').classList.add('hidden');
}
</script>
