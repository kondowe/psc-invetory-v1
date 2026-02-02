<?php if (!empty($lowStockAlerts)): ?>
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Stock Alerts
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($lowStockAlerts as $alert): ?>
                <div class="bg-white border rounded-lg p-4 shadow-sm flex flex-col justify-between <?= $alert['alert_level'] === 'critical' ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50' ?>">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold uppercase px-2 py-0.5 rounded <?= $alert['alert_level'] === 'critical' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800' ?>">
                                <?= $alert['alert_level'] ?>
                            </span>
                            <span class="text-xs text-gray-500 font-mono"><?= Security::e($alert['sku']) ?></span>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1"><?= Security::e($alert['item_name']) ?></h4>
                        <p class="text-sm text-gray-600 mb-2">Store: <?= Security::e($alert['store_name']) ?></p>
                    </div>
                    
                    <div class="border-t pt-2 mt-2 flex justify-between items-end">
                        <div>
                            <p class="text-xs text-gray-500">Available</p>
                            <p class="text-lg font-bold <?= $alert['alert_level'] === 'critical' ? 'text-red-600' : 'text-yellow-600' ?>">
                                <?= number_format($alert['quantity_available'], 2) ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Threshold</p>
                            <p class="text-sm font-medium text-gray-700">
                                <?= $alert['alert_level'] === 'critical' ? $alert['minimum_stock_level'] : $alert['reorder_level'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
