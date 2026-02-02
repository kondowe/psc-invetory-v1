<?php $pageTitle = 'New Request'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">New Requisition</h2>
</div>

<!-- Tabs Navigation -->
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <button onclick="switchTab('item')" id="itemTab" class="tab-btn border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            General Items Request
        </button>
        <button onclick="switchTab('fuel')" id="fuelTab" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            Fuel Request
        </button>
    </nav>
</div>

<!-- GENERAL ITEMS FORM -->
<div id="itemFormContainer">
    <form action="<?= Security::url('/requests/store') ?>" method="POST" id="itemRequestForm">
        <?= Security::csrfInput() ?>
        <input type="hidden" name="request_type" value="item">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Request Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                            <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Required *</label>
                            <input type="date" name="date_required" required value="<?= date('Y-m-d', strtotime('+3 days')) ?>"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose / Justification *</label>
                            <textarea name="purpose" required rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Why are these items needed?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Items Requested</h3>
                        <button type="button" onclick="addItemRow('item')" class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded hover:bg-blue-100 font-medium">+ Add Item</button>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Item Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Item Name / Selection</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="itemItemsBody">
                            <tr class="item-row">
                                <td class="px-6 py-4">
                                    <select name="items[0][is_custom]" onchange="toggleItemType(this)" class="item-type-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="0">Inventory Item</option>
                                        <option value="1">Custom Item</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inventory-select-wrapper">
                                        <select name="items[0][item_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Select Item</option>
                                            <?php foreach ($items as $i): ?>
                                                <option value="<?= $i['item_id'] ?>"><?= Security::e($i['item_name']) ?> (<?= Security::e($i['sku']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="custom-input-wrapper hidden">
                                        <input type="text" name="items[0][custom_item_name]" placeholder="Enter item name..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" step="0.01" name="items[0][quantity]" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="removeRow(this)" class="text-red-600 hover:text-red-900">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Submission</h3>
                    <div class="space-y-2">
                        <button type="submit" name="action_save" value="1" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-medium">Save Draft</button>
                        <button type="submit" name="action_submit" value="1" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Submit for Approval</button>
                        <a href="<?= Security::url('/requests') ?>" class="block text-center text-sm text-gray-500 hover:text-gray-700 py-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- FUEL REQUEST FORM -->
<div id="fuelFormContainer" class="hidden">
    <form action="<?= Security::url('/requests/store') ?>" method="POST" id="fuelRequestForm">
        <?= Security::csrfInput() ?>
        <input type="hidden" name="request_type" value="fuel">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Trip & Vehicle Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departure Point *</label>
                            <input type="text" name="departure_point" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Start location">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Destination Point *</label>
                            <input type="text" name="destination_point" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="End location">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departure Date *</label>
                            <input type="date" name="departure_date" required value="<?= date('Y-m-d') ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="is_round_trip" id="is_round_trip_fuel" value="1" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-gray-300">
                            <label for="is_round_trip_fuel" class="ml-2 block text-sm text-gray-900">Round Trip?</label>
                        </div>
                        <hr class="md:col-span-2">
                     
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="request_company_vehicle" id="request_company_vehicle_fuel" value="1" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4 border-gray-300" onchange="toggleVehicleSelect(this)">
                            <label for="request_company_vehicle_fuel" class="ml-2 block text-sm text-gray-900">Requesting a Company Vehicle?</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Type *</label>
                            <select name="fuel_type_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Select Fuel Type --</option>
                                <?php foreach ($fuelTypes as $ft): ?>
                                    <option value="<?= $ft['fuel_type_id'] ?>"><?= Security::e($ft['fuel_type_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Liters Requested *</label>
                            <input type="number" step="0.01" name="fuel_quantity" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. 200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose / Justification *</label>
                            <textarea name="purpose" required rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Justification for fuel..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                            <select name="priority" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <input type="hidden" name="date_required" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Submission</h3>
                    <div class="space-y-2">
                        <button type="submit" name="action_save" value="1" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-medium">Save Draft</button>
                        <button type="submit" name="action_submit" value="1" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Submit for Approval</button>
                        <a href="<?= Security::url('/requests') ?>" class="block text-center text-sm text-gray-500 hover:text-gray-700 py-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let rowCount = 1;

function switchTab(type) {
    const itemTab = document.getElementById('itemTab');
    const fuelTab = document.getElementById('fuelTab');
    const itemContainer = document.getElementById('itemFormContainer');
    const fuelContainer = document.getElementById('fuelFormContainer');
    
    if (type === 'item') {
        itemTab.className = 'tab-btn border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
        fuelTab.className = 'tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
        itemContainer.classList.remove('hidden');
        fuelContainer.classList.add('hidden');
    } else {
        fuelTab.className = 'tab-btn border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
        itemTab.className = 'tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
        fuelContainer.classList.remove('hidden');
        itemContainer.classList.add('hidden');
    }
}

function toggleVehicleSelect(checkbox) {
    const vehicleSelect = document.getElementById('vehicle_id_fuel');
    vehicleSelect.disabled = checkbox.checked;
    if (checkbox.checked) vehicleSelect.value = '';
}

function toggleItemType(select) {
    const row = select.closest('tr');
    const inventoryWrapper = row.querySelector('.inventory-select-wrapper');
    const customWrapper = row.querySelector('.custom-input-wrapper');
    const inventorySelect = inventoryWrapper.querySelector('select');
    const customInput = customWrapper.querySelector('input');

    if (select.value == '1') {
        inventoryWrapper.classList.add('hidden');
        customWrapper.classList.remove('hidden');
        inventorySelect.removeAttribute('required');
        customInput.setAttribute('required', 'required');
    } else {
        inventoryWrapper.classList.remove('hidden');
        customWrapper.classList.add('hidden');
        inventorySelect.setAttribute('required', 'required');
        customInput.removeAttribute('required');
    }
}

function addItemRow(type) {
    const tbody = document.getElementById('itemItemsBody');
    const firstRow = tbody.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    
    // Reset selections and visibility
    const selects = newRow.querySelectorAll('select');
    const inputs = newRow.querySelectorAll('input');
    
    selects.forEach(s => { 
        s.name = s.name.replace(/\[\d+\]/, `[${rowCount}]`); 
        s.value = s.classList.contains('item-type-select') ? '0' : '';
    });
    
    inputs.forEach(i => { 
        i.name = i.name.replace(/\[\d+\]/, `[${rowCount}]`); 
        i.value = ''; 
    });

    // Ensure first wrapper is visible, second hidden
    newRow.querySelector('.inventory-select-wrapper').classList.remove('hidden');
    newRow.querySelector('.custom-input-wrapper').classList.add('hidden');
    newRow.querySelector('.inventory-select-wrapper select').setAttribute('required', 'required');
    newRow.querySelector('.custom-input-wrapper input').removeAttribute('required');

    tbody.appendChild(newRow);
    rowCount++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('#itemItemsBody .item-row');
    if (rows.length > 1) btn.closest('tr').remove();
}
</script>
