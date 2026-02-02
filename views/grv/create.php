<?php $pageTitle = 'Create Goods Received Voucher'; ?>

<div class="mb-6">
    <a href="<?= Security::url('/grv') ?>" class="text-blue-600 hover:text-blue-800 flex items-center mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to GRVs
    </a>
    <h2 class="text-2xl font-bold text-gray-800">Create Goods Received Voucher</h2>
</div>

<form action="<?= Security::url('/grv/store') ?>" method="POST" id="grvForm">
    <?= Security::csrfInput() ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Header Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier *</label>
                        <select name="supplier_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Supplier</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['supplier_id'] ?>"><?= Security::e($s['supplier_name']) ?> (<?= Security::e($s['supplier_code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Store / Warehouse *</label>
                        <select name="store_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Store</option>
                            <?php foreach ($stores as $s): ?>
                                <option value="<?= $s['store_id'] ?>"><?= Security::e($s['store_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Received Date *</label>
                        <input type="date" name="received_date" required value="<?= date('Y-m-d') ?>"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference Type</label>
                        <select name="reference_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="purchase_order">Purchase Order</option>
                            <option value="donation">Donation</option>
                            <option value="transfer">Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                        <input type="text" name="reference_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="PO # or Invoice #">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Items Received</h3>
                    <button type="button" onclick="addItemRow()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Item
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Item Details</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty & Cost</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch & Expiry</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="itemsBody">
                            <tr class="item-row">
                                <td class="px-4 py-4">
                                    <div class="mb-2 flex items-center justify-between">
                                        <label class="inline-flex items-center text-xs font-medium text-gray-500">
                                            <input type="checkbox" name="items[0][is_new]" class="new-item-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 mr-2" onchange="toggleNewItem(this)">
                                            New Item?
                                        </label>
                                    </div>
                                    
                                    <div class="existing-item-container">
                                        <select name="items[0][item_id]" class="item-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm mb-2" required onchange="handleItemChange(this)">
                                            <option value="">Select Existing Item</option>
                                            <?php foreach ($items as $i): ?>
                                                <option value="<?= $i['item_id'] ?>" 
                                                        data-cost="<?= $i['unit_cost'] ?>" 
                                                        data-is-fuel="<?= $i['is_fuel_category'] ?>"
                                                        data-category="<?= $i['category_id'] ?>">
                                                    <?= Security::e($i['item_name']) ?> (<?= Security::e($i['sku']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="new-item-fields hidden space-y-2">
                                        <input type="text" name="items[0][new_sku]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="New SKU *">
                                        <input type="text" name="items[0][new_name]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="New Item Name *">
                                        <div class="grid grid-cols-2 gap-2">
                                            <select name="items[0][new_category_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs" onchange="handleNewCategoryChange(this)">
                                                <option value="">Category *</option>
                                                <?php foreach ($categories as $c): ?>
                                                    <option value="<?= $c['category_id'] ?>" data-is-fuel="<?= $c['is_fuel_category'] ?>"><?= Security::e($c['display_name'] ?? $c['category_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select name="items[0][new_uom_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs">
                                                <option value="">UOM *</option>
                                                <?php foreach ($uoms as $u): ?>
                                                    <option value="<?= $u['uom_id'] ?>"><?= Security::e($u['uom_code']) ?> (<?= Security::e($u['uom_name']) ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Fuel Coupon Details (Hidden by default) -->
                                    <input type="hidden" name="items[0][is_fuel_coupon]" class="is-fuel-input" value="0">
                                    <div class="fuel-details hidden mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100 space-y-2">
                                        <p class="text-[10px] font-bold text-blue-800 uppercase">Coupon Tracking</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            <input type="text" name="items[0][coupon_serial_from]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs" placeholder="Serial From">
                                            <input type="text" name="items[0][coupon_serial_to]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs" placeholder="Serial To">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <select name="items[0][fuel_type_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs">
                                                <option value="">Fuel Type *</option>
                                                <?php foreach ($fuelTypes as $ft): ?>
                                                    <option value="<?= $ft['fuel_type_id'] ?>"><?= Security::e($ft['fuel_type_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="number" step="0.01" name="items[0][coupon_value]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs" placeholder="Liters per Coupon">
                                        </div>
                                    </div>

                                    <input type="text" name="items[0][notes]" class="w-full rounded-md border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs mt-2" placeholder="Item specific notes...">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center">
                                            <span class="text-xs text-gray-500 w-8">Qty:</span>
                                            <input type="number" step="0.01" name="items[0][quantity]" required class="qty-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" onchange="calculateRow(this)">
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-xs text-gray-500 w-8">Cost:</span>
                                            <input type="number" step="0.01" name="items[0][unit_cost]" required class="cost-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" onchange="calculateRow(this)">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <input type="text" name="items[0][batch_number]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Batch #">
                                        <input type="date" name="items[0][expiry_date]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-bold row-total text-right">0.00</td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Summary</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-medium text-gray-600">Total Items:</span>
                        <span class="font-bold text-gray-800" id="itemCount">1</span>
                    </div>
                    <div class="flex justify-between items-center text-xl">
                        <span class="font-medium text-gray-600">Total Value:</span>
                        <span class="font-bold text-blue-600" id="grandTotal">0.00</span>
                    </div>
                    <div class="pt-4 border-t">
                        <label class="block text-sm font-medium text-gray-700 mb-1">General Notes</label>
                        <textarea name="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Additional information about this shipment..."></textarea>
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <button type="submit" name="submit_for_approval" value="1" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-bold shadow-md flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Submit for Approval
                    </button>
                    <button type="submit" name="save_draft" value="1" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-3 rounded-lg font-medium">
                        Save as Draft
                    </button>
                    <a href="<?= Security::url('/grv') ?>" class="block text-center text-sm text-gray-500 hover:text-gray-700 py-2">Discard Changes</a>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <h4 class="text-sm font-bold text-blue-800 mb-1">Information</h4>
                <p class="text-xs text-blue-700">GRVs saved as Draft can be edited later. Once submitted for approval, they will move to the next stage in the workflow and stock will only be updated upon final approval.</p>
            </div>
        </div>
    </div>
</form>

<script>
let rowCount = 1;

function handleItemChange(select) {
    const isFuel = select.options[select.selectedIndex].dataset.isFuel == '1';
    const row = select.closest('tr');
    const fuelDetails = row.querySelector('.fuel-details');
    const isFuelInput = row.querySelector('.is-fuel-input');
    
    if (isFuel) {
        fuelDetails.classList.remove('hidden');
        isFuelInput.value = '1';
    } else {
        fuelDetails.classList.add('hidden');
        isFuelInput.value = '0';
    }
}

function handleNewCategoryChange(select) {
    const isFuel = select.options[select.selectedIndex].dataset.isFuel == '1';
    const row = select.closest('tr');
    const fuelDetails = row.querySelector('.fuel-details');
    const isFuelInput = row.querySelector('.is-fuel-input');
    
    if (isFuel) {
        fuelDetails.classList.remove('hidden');
        isFuelInput.value = '1';
    } else {
        fuelDetails.classList.add('hidden');
        isFuelInput.value = '0';
    }
}

function toggleNewItem(checkbox) {
    const row = checkbox.closest('.item-row');
    const existingContainer = row.querySelector('.existing-item-container');
    const newFields = row.querySelector('.new-item-fields');
    const fuelDetails = row.querySelector('.fuel-details');
    const existingSelect = existingContainer.querySelector('select');
    const newInputs = newFields.querySelectorAll('input, select');

    if (checkbox.checked) {
        existingContainer.classList.add('hidden');
        newFields.classList.remove('hidden');
        existingSelect.removeAttribute('required');
        existingSelect.value = '';
        newInputs.forEach(input => input.setAttribute('required', 'required'));
        fuelDetails.classList.add('hidden'); // Hide until category selected
    } else {
        existingContainer.classList.remove('hidden');
        newFields.classList.add('hidden');
        existingSelect.setAttribute('required', 'required');
        newInputs.forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
        handleItemChange(existingSelect); // Re-evaluate based on select
    }
    calculateRow(checkbox);
}

function addItemRow() {
    const tbody = document.getElementById('itemsBody');
    const firstRow = tbody.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    
    // Update names and reset values
    const selects = newRow.querySelectorAll('select');
    const inputs = newRow.querySelectorAll('input');
    const checkboxes = newRow.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(c => {
        c.name = c.name.replace(/\[\d+\]/, `[${rowCount}]`);
        c.checked = false;
    });

    selects.forEach(s => {
        s.name = s.name.replace(/\[\d+\]/, `[${rowCount}]`);
        s.value = '';
    });
    
    inputs.forEach(i => {
        if (i.type !== 'checkbox') {
            i.name = i.name.replace(/\[\d+\]/, `[${rowCount}]`);
            i.value = '';
        }
    });
    
    // Reset visibility
    newRow.querySelector('.existing-item-container').classList.remove('hidden');
    newRow.querySelector('.new-item-fields').classList.add('hidden');
    newRow.querySelector('.fuel-details').classList.add('hidden');
    newRow.querySelector('.existing-item-container select').setAttribute('required', 'required');
    newRow.querySelector('.new-item-fields').querySelectorAll('input, select').forEach(input => {
        input.removeAttribute('required');
    });

    newRow.querySelector('.row-total').textContent = '0.00';
    tbody.appendChild(newRow);
    rowCount++;

    updateItemCount();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
        calculateGrandTotal();
        updateItemCount();
    } else {
        alert('At least one item is required.');
    }
}

function calculateRow(input) {
    const row = input.closest('tr');
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
    const total = qty * cost;
    row.querySelector('.row-total').textContent = total.toFixed(2);
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.row-total').forEach(el => {
        grandTotal += parseFloat(el.textContent) || 0;
    });
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

function updateItemCount() {
    const count = document.querySelectorAll('.item-row').length;
    document.getElementById('itemCount').textContent = count;
}

// Item selection auto-cost
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-select')) {
        const select = e.target;
        const cost = select.options[select.selectedIndex].dataset.cost || 0;
        const costInput = select.closest('tr').querySelector('.cost-input');
        if (cost > 0) {
            costInput.value = cost;
        }
        calculateRow(costInput);
    }
});

// Initial setup
window.addEventListener('DOMContentLoaded', (event) => {
    updateItemCount();
});
</script>

