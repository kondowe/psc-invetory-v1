<?php $pageTitle = 'Request Details - ' . $request['request_number']; ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Request #<?= $request['request_number'] ?></h2>
    </div>
    <div>
        <?php if ($request['status'] == 'draft'): ?>
            <form action="<?= Security::url('/requests/submit/' . $request['request_id']) ?>" method="POST" class="inline">
                <?= Security::csrfInput() ?>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold">Submit for Approval</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Requested Items</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Approved</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <?php if ($currentPendingStep && (Auth::isAdminManager() || Auth::isGeneralAdminManager())): ?>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Adjust</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                        <tr id="item-row-<?= $item['request_item_id'] ?>">
                            <td class="px-6 py-4">
                                <?php if ($item['is_custom']): ?>
                                    <div class="text-sm font-medium text-gray-900"><?= Security::e($item['custom_item_name']) ?></div>
                                    <div class="text-xs text-blue-600 font-bold uppercase tracking-wider">Custom Item</div>
                                <?php else: ?>
                                    <div class="text-sm font-medium text-gray-900"><?= Security::e($item['item_name']) ?></div>
                                    <div class="text-xs text-gray-500 font-mono"><?= Security::e($item['sku']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php 
                                $isManager = Auth::hasRole([ROLE_ADMIN_MGR, ROLE_GENERAL_ADMIN_MGR]);
                                if ($currentPendingStep && $isManager): 
                                ?>
                                    <div class="flex items-center space-x-2">
                                        <input type="number" step="0.01" value="<?= $item['quantity_requested'] ?>" 
                                               onchange="updateItemQty(<?= $item['request_item_id'] ?>, this.value)"
                                               class="w-20 rounded border-gray-300 text-sm p-1 focus:ring-blue-500 focus:border-blue-500 bg-yellow-50">
                                        <span class="text-xs text-gray-500"><?= Security::e($item['uom_code'] ?? 'Units') ?></span>
                                    </div>
                                <?php else: ?>
                                    <?= number_format($item['quantity_requested'], 2) ?> <?= Security::e($item['uom_code'] ?? 'Units') ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= number_format($item['quantity_approved'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </td>
                            <?php if ($currentPendingStep && (Auth::isAdminManager() || Auth::isGeneralAdminManager())): ?>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="removeItem(<?= $item['request_item_id'] ?>)" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($request['request_type'] === 'fuel'): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Trip & Vehicle Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Departure Point</p>
                        <p class="text-sm text-gray-900"><?= Security::e($request['departure_point']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Destination Point</p>
                        <p class="text-sm text-gray-900"><?= Security::e($request['destination_point']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Departure Date</p>
                        <p class="text-sm text-gray-900"><?= $request['departure_date'] ? date('d M Y', strtotime($request['departure_date'])) : 'N/A' ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Round Trip</p>
                        <p class="text-sm text-gray-900"><?= $request['is_round_trip'] ? 'Yes' : 'No' ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-medium text-gray-500 uppercase">Vehicle Detail</p>
                        <p class="text-sm text-gray-900">
                            <?php if ($request['request_company_vehicle']): ?>
                                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold uppercase">Requested Company Vehicle</span>
                            <?php elseif ($request['vehicle_number']): ?>
                                <?= Security::e($request['vehicle_number']) ?>
                            <?php else: ?>
                                <span class="text-gray-400 italic">Not specified</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Workflow Progress</h3>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <?php foreach ($workflowSteps as $index => $step): ?>
                        <li>
                            <div class="relative pb-8">
                                <?php if ($index !== count($workflowSteps) - 1): ?>
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <?php endif; ?>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                            <?= $step['status'] == 'approved' ? 'bg-green-500' : ($step['status'] == 'pending' ? 'bg-yellow-500' : 'bg-gray-400') ?>">
                                            <?php if ($step['status'] == 'approved'): ?>
                                                <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            <?php else: ?>
                                                <span class="text-xs text-white"><?= $index + 1 ?></span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500"><?= Security::e($step['step_name']) ?> <span class="font-medium text-gray-900">(<?= Security::e($step['role_name']) ?>)</span></p>
                                            <?php if ($step['comments']): ?>
                                                <p class="mt-1 text-xs text-gray-500 italic">"<?= Security::e($step['comments']) ?>"</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-right text-sm调节 whitespace-nowrap text-gray-500">
                                            <span class="font-bold"><?= ucfirst($step['status']) ?></span>
                                            <?php if ($step['action_date']): ?>
                                                <br><time><?= date('d M, H:i', strtotime($step['action_date'])) ?></time>
                                                <br><span class="text-xs">by <?= Security::e($step['action_by_name']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <?php if (Auth::isGeneralAdminManager() && $request['status'] === 'pending'): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-red-900 mb-2">Administrative Override</h3>
                <p class="text-sm text-red-700 mb-4">As General Administration Manager, you can bypass the remaining workflow steps and approve this request immediately.</p>
                <form action="<?= Security::url('/requests/override/' . $request['request_id']) ?>" method="POST" onsubmit="return confirm('CRITICAL ACTION: Are you sure you want to bypass all remaining approvals and force-approve this request?');">
                    <?= Security::csrfInput() ?>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        Force Approve Request
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($currentPendingStep): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-bold text-blue-900 mb-2">Action Required</h3>
                <p class="text-sm text-blue-700 mb-4">This request is currently awaiting your approval at the <strong><?= Security::e($currentPendingStep['step_name'] ?? 'Current') ?></strong> step.</p>
                
                <form id="detailApprovalForm" method="POST" class="space-y-4">
                    <?= Security::csrfInput() ?>
                    <textarea name="comments" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Add comments (optional)..."></textarea>
                    <div class="flex space-x-3">
                        <button type="button" onclick="submitAction('approve')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                            Approve Request
                        </button>
                        <button type="button" onclick="submitAction('reject')" class="flex-1 bg-red-50 text-red-600 hover:bg-red-100 font-bold py-2 px-4 rounded-lg border border-red-200 transition">
                            Reject
                        </button>
                    </div>
                </form>
            </div>

            <script>
            function submitAction(action) {
                const form = document.getElementById('detailApprovalForm');
                const confirmMsg = action === 'approve' ? 'Are you sure you want to approve this request?' : 'Are you sure you want to reject this request?';
                
                if (confirm(confirmMsg)) {
                    form.action = `<?= Security::url('/requests/') ?>${action}/<?= $currentPendingStep['workflow_step_instance_id'] ?>`;
                    form.submit();
                }
            }
            </script>
        <?php endif; ?>

        <script>
        function updateItemQty(reqItemId, newQty) {
            if (newQty <= 0) {
                alert('Quantity must be greater than zero. Use the delete button to remove an item.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`<?= Security::url('/requests/updateItem/') ?>${reqItemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) alert('Error: ' + data.message);
            })
            .catch(err => alert('Failed to update quantity'));
        }

        function removeItem(reqItemId) {
            if (!confirm('Are you sure you want to remove this item from the request?')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(`<?= Security::url('/requests/removeItem/') ?>${reqItemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`item-row-${reqItemId}`).remove();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => alert('Failed to remove item'));
        }
        </script>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Information</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Status</p>
                    <span class="px-2 py-1 text-sm font-bold rounded-full 
                        <?= $request['status'] == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= strtoupper($request['status']) ?>
                    </span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Requester</p>
                    <p class="text-sm text-gray-900 font-medium"><?= Security::e($request['requester_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= Security::e($request['department_name']) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Date Required</p>
                    <p class="text-sm text-gray-900"><?= date('d M Y', strtotime($request['date_required'])) ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Purpose</p>
                    <p class="text-sm text-gray-700"><?= nl2br(Security::e($request['purpose'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
