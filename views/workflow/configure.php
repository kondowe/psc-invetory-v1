<?php $pageTitle = 'Workflow Configuration'; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Workflow Configuration</h2>
        <p class="text-gray-600 text-sm">Define how requests are approved in your department.</p>
    </div>
</div>

<?php if (Auth::isGeneralAdminManager()): ?>
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="<?= Security::url('/workflow/configure') ?>" method="GET" class="flex items-end space-x-4">
        <div class="w-64">
            <label class="block text-sm font-medium text-gray-700 mb-1">Select Department</label>
            <select name="dept_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['department_id'] ?>" <?= $currentDeptId == $dept['department_id'] ? 'selected' : '' ?>>
                        <?= Security::e($dept['department_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">Switch</button>
    </form>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Department Templates</h3>
                <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-800 text-sm font-bold">+ Create</button>
            </div>
            <div class="p-6">
                <?php if (empty($templates)): ?>
                    <p class="text-gray-500 text-center py-4 italic">No custom templates defined.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($templates as $t): ?>
                            <div class="border rounded-lg p-4 flex justify-between items-center hover:bg-gray-50 transition">
                                <div>
                                    <h4 class="font-bold text-gray-900"><?= Security::e($t['template_name']) ?></h4>
                                    <p class="text-xs text-gray-500 uppercase font-medium">Type: <?= Security::e($t['request_type']) ?></p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="<?= Security::url('/workflow/viewSteps/' . $t['workflow_template_id']) ?>" class="text-blue-600 hover:underline text-sm font-bold">Manage Steps →</a>
                                    <form action="<?= Security::url('/workflow/deleteTemplate/' . $t['workflow_template_id']) ?>" method="POST" onsubmit="return confirm('Delete this template and all its steps?')">
                                        <?= Security::csrfInput() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create Template Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Create Workflow Template</h3>
                <form action="<?= Security::url('/workflow/createTemplate') ?>" method="POST" class="space-y-4">
                    <?= Security::csrfInput() ?>
                    <input type="hidden" name="department_id" value="<?= $currentDeptId ?>">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Template Name *</label>
                        <input type="text" name="template_name" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Standard Requisition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Request Type</label>
                        <select name="request_type" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="both">Both Item & Fuel</option>
                            <option value="item">Items Only</option>
                            <option value="fuel">Fuel Only</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('createModal').classList.add('hidden');
    }
    </script>

    <?php if (Auth::isGeneralAdminManager()): ?>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">System Templates (Mandatory)</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php foreach ($globalTemplates as $t): ?>
                        <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-gray-900"><?= Security::e($t['template_name']) ?></h4>
                                <p class="text-xs text-gray-600">These steps are appended to all requests automatically.</p>
                            </div>
                            <a href="<?= Security::url('/workflow/viewSteps/' . $t['workflow_template_id']) ?>" class="text-yellow-700 hover:underline text-sm font-bold">View →</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
