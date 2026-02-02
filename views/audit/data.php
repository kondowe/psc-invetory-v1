<?php $pageTitle = 'Data Audit Trail'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Data Audit Trail</h2>
    <div class="flex space-x-2">
        <a href="<?= Security::url('/audit') ?>" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm font-medium">Activity Logs</a>
        <a href="<?= Security::url('/audit/data') ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Data Audit</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($audits)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No data changes recorded yet</td>
                </tr>
            <?php else: ?>
                <?php foreach ($audits as $log): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('d M Y H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?= Security::e($log['full_name'] ?: 'System') ?></div>
                            <div class="text-xs text-gray-500"><?= Security::e($log['username'] ?: '') ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= Security::e($log['table_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php
                                switch($log['action']) {
                                    case 'create': echo 'bg-green-100 text-green-800'; break;
                                    case 'update': echo 'bg-yellow-100 text-yellow-800'; break;
                                    case 'delete': echo 'bg-red-100 text-red-800'; break;
                                    default: echo 'bg-blue-100 text-blue-800';
                                }
                                ?>">
                                <?= strtoupper(Security::e($log['action'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #<?= $log['record_id'] ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <button onclick='showAuditDetail(<?= json_encode($log) ?>)' class="text-blue-600 hover:text-blue-900">View Changes</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for details -->
<div id="auditModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto p-4 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">Audit Detail</h3>
            <button onclick="closeAuditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" id="modalContent">
            <!-- Dynamic Content -->
        </div>
    </div>
</div>

<script>
function showAuditDetail(log) {
    const content = document.getElementById('modalContent');
    const oldVals = log.old_values ? JSON.parse(log.old_values) : null;
    const newVals = log.new_values ? JSON.parse(log.new_values) : null;

    let html = `
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Table</p>
                <p class="text-lg font-semibold text-gray-800">${log.table_name}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase">Action</p>
                <p class="text-lg font-semibold text-gray-800 uppercase">${log.action}</p>
            </div>
        </div>
    `;

    if (oldVals || newVals) {
        html += `<div class="space-y-4">`;
        
        // Get unique keys from both
        const keys = new Set([...Object.keys(oldVals || {}), ...Object.keys(newVals || {})]);
        
        html += `<table class="min-w-full border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 border text-left text-xs font-bold uppercase">Field</th>
                    <th class="px-4 py-2 border text-left text-xs font-bold uppercase">Old Value</th>
                    <th class="px-4 py-2 border text-left text-xs font-bold uppercase">New Value</th>
                </tr>
            </thead>
            <tbody>`;

        keys.forEach(key => {
            const oldV = oldVals ? oldVals[key] : '-';
            const newV = newVals ? newVals[key] : '-';
            const isDifferent = JSON.stringify(oldV) !== JSON.stringify(newV);
            
            html += `<tr class="${isDifferent ? 'bg-yellow-50' : ''}">
                <td class="px-4 py-2 border font-medium text-sm">${key}</td>
                <td class="px-4 py-2 border text-sm text-gray-600">${typeof oldV === 'object' ? JSON.stringify(oldV) : oldV}</td>
                <td class="px-4 py-2 border text-sm text-gray-800 ${isDifferent ? 'font-bold' : ''}">${typeof newV === 'object' ? JSON.stringify(newV) : newV}</td>
            </tr>`;
        });

        html += `</tbody></table></div>`;
    } else {
        html += `<p class="text-gray-500 italic">No value changes recorded</p>`;
    }

    content.innerHTML = html;
    document.getElementById('auditModal').classList.remove('hidden');
}

function closeAuditModal() {
    document.getElementById('auditModal').classList.add('hidden');
}
</script>
