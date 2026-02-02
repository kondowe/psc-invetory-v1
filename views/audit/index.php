<?php $pageTitle = 'Activity Trail'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">System Activity Trail</h2>
    <div class="flex space-x-2">
        <a href="<?= Security::url('/audit') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Activity Logs</a>
        <a href="<?= Security::url('/audit/data') ?>" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm font-medium">Data Audit</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($activities)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No activities recorded yet</td>
                </tr>
            <?php else: ?>
                <?php foreach ($activities as $log): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d M Y H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($log['full_name'] ?: 'System') ?></div>
                            <div class="text-xs text-gray-500"><?= Security::e($log['username'] ?: '') ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                                <?= str_replace('_', ' ', Security::e($log['activity_type'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            <?= Security::e($log['description']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($log['ip_address']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex justify-center">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?= Security::url('/audit?page=' . $i) ?>" class="<?= $i === $currentPage ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' ?> relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    </div>
<?php endif; ?>
