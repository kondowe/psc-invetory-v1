<?php $pageTitle = 'Request Management & Controls'; ?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Request Management & Controls</h2>
    <p class="text-gray-600">Define which roles can request items based on their current stock levels.</p>
</div>

<form action="<?= Security::url('/request-management/updateSettings') ?>" method="POST">
    <?= Security::csrfInput() ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Stock Level Restrictions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Stock-Level Based Restrictions</h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="enforce_stock_restrictions" value="1" <?= $settings['enforce'] ? 'checked' : '' ?> class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-700">Enforce Rules</span>
                </label>
            </div>
            
            <div class="p-6 space-y-8">
                <!-- Below Reorder Level -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Roles allowed when item is below <span class="text-yellow-600">Reorder Level</span></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach ($roles as $role): ?>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_below_reorder[]" value="<?= $role['role_key'] ?>" 
                                    <?= in_array($role['role_key'], $settings['below_reorder']) ? 'checked' : '' ?>
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 font-medium"><?= Security::e($role['role_name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 italic">Users not in these roles will be blocked from requesting items that have reached their reorder point.</p>
                </div>

                <!-- Below Minimum Level -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Roles allowed when item is below <span class="text-red-600">Minimum Level</span></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach ($roles as $role): ?>
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="roles_below_min[]" value="<?= $role['role_key'] ?>" 
                                    <?= in_array($role['role_key'], $settings['below_min']) ? 'checked' : '' ?>
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700 font-medium"><?= Security::e($role['role_name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 italic">Highly restricted. Only selected roles can request items that are at critical minimum stock.</p>
                </div>
            </div>
        </div>

        <!-- Explainer/Policy Info -->
        <div class="space-y-6">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-blue-800 font-bold mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    How these rules work
                </h3>
                <ul class="text-sm text-blue-700 space-y-2 list-disc ml-5">
                    <li>When <strong>Enforce Rules</strong> is on, the system checks the total available stock across all stores for every item in a new request.</li>
                    <li>If an item is below its defined <strong>Minimum Level</strong>, only users with roles selected in the second box can proceed.</li>
                    <li>If an item is below its <strong>Reorder Level</strong>, only users with roles in the first box can proceed.</li>
                    <li>Requesters will receive a clear explanation message if they are blocked.</li>
                </ul>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Finalize Settings</h3>
                <p class="text-sm text-gray-600 mb-6">Changes to these policies take effect immediately for all new requisitions.</p>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold shadow-sm transition">
                    Save Requisition Policies
                </button>
            </div>
        </div>
    </div>
</form>
