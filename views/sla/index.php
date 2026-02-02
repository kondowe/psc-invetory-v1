<?php $pageTitle = 'SLA Management'; ?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">SLA Management</h2>
    <p class="text-gray-600">Configure system-wide Service Level Agreements and workflow step timers.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Global SLA Settings -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800">Global Settings</h3>
            </div>
            <form action="<?= Security::url('/sla/updateGlobal') ?>" method="POST" class="p-6 space-y-4">
                <?= Security::csrfInput() ?>
                
                <?php foreach ($globalSlas as $key => $config): ?>
                    <?php if (!$config) continue; ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <?= Security::e($config['description']) ?>
                        </label>
                        <?php if ($config['config_type'] === 'boolean'): ?>
                            <select name="config[<?= $key ?>]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="true" <?= $config['config_value'] === 'true' ? 'selected' : '' ?>>Enabled</option>
                                <option value="false" <?= $config['config_value'] === 'false' ? 'selected' : '' ?>>Disabled</option>
                            </select>
                        <?php else: ?>
                            <div class="flex items-center">
                                <input type="number" name="config[<?= $key ?>]" value="<?= Security::e($config['config_value']) ?>"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-gray-500 text-sm">hours</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Update Global Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Workflow Step SLAs -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Workflow Step SLAs</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Individual steps</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Template & Step</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Approver</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">SLA (Hours)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($stepSlas as $step): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?= Security::e($step['template_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= $step['step_order'] ?>. <?= Security::e($step['step_name']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= Security::e($step['role_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <form id="form-step-<?= $step['workflow_step_id'] ?>" action="<?= Security::url('/sla/updateStep') ?>" method="POST" class="flex items-center space-x-2">
                                        <?= Security::csrfInput() ?>
                                        <input type="hidden" name="workflow_step_id" value="<?= $step['workflow_step_id'] ?>">
                                        <input type="number" name="sla_hours" value="<?= $step['sla_hours'] ?>" 
                                            class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 font-bold text-xs uppercase tracking-wider">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- Action reserved for more complex edits if needed -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>