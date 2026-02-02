<?php $pageTitle = 'Pending Approvals'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Pending Approvals</h2>
    <p class="text-gray-600 text-sm">Requests awaiting your action.</p>
</div>

<?php if (empty($pendingActions)): ?>
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No pending approvals</h3>
        <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($pendingActions as $action): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center">
                            <h3 class="text-lg font-bold text-gray-900 mr-2"><?= $action['request_number'] ?></h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= ucfirst($action['request_type']) ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">From: <span class="font-medium text-gray-700"><?= Security::e($action['requester_name']) ?></span></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-medium text-gray-500 uppercase block">Step</span>
                        <span class="text-sm font-bold text-blue-600"><?= Security::e($action['step_name']) ?></span>
                    </div>
                </div>
                
                <div class="mb-4 bg-gray-50 p-3 rounded text-sm text-gray-700">
                    <p class="font-medium mb-1 uppercase text-[10px] text-gray-500">Purpose:</p>
                    <?= Security::e($action['purpose']) ?>
                </div>

                <div class="flex justify-between items-center pt-4 border-t">
                    <a href="<?= Security::url('/requests/view/' . $action['request_id']) ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Full Details →</a>
                    <div class="flex space-x-2">
                        <button onclick="openApprovalModal(<?= $action['workflow_step_instance_id'] ?>, 'reject')" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm font-bold">Reject</button>
                        <button onclick="openApprovalModal(<?= $action['workflow_step_instance_id'] ?>, 'approve')" class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg text-sm font-bold">Approve</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Approval Modal -->
<div id="approvalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Approve Request</h3>
            <form id="modalForm" method="POST" class="mt-4 text-left">
                <?= Security::csrfInput() ?>
                <label class="block text-sm font-medium text-gray-700 mb-1">Comments (optional)</label>
                <textarea name="comments" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                <div class="mt-5 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg text-sm">Cancel</button>
                    <button type="submit" id="modalSubmit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApprovalModal(id, action) {
    const modal = document.getElementById('approvalModal');
    const form = document.getElementById('modalForm');
    const title = document.getElementById('modalTitle');
    const submit = document.getElementById('modalSubmit');
    
    form.action = `<?= Security::url('/requests/') ?>${action}/${id}`;
    title.textContent = action === 'approve' ? 'Approve Request' : 'Reject Request';
    submit.className = action === 'approve' ? 'px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold' : 'px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold';
    submit.textContent = action === 'approve' ? 'Confirm Approval' : 'Confirm Rejection';
    
    modal.classList.remove('hidden');
}
function closeModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}
</script>
