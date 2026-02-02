<?php $pageTitle = 'Fuel Coupons'; ?>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Fuel Coupon Inventory</h2>
    <div class="flex space-x-2">
        <a href="/fuel/vehicles" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            Manage Vehicles
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial #</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Date</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($coupons)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No coupons found in system</td>
                </tr>
            <?php else: ?>
                <?php foreach ($coupons as $c): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-gray-900"><?= Security::e($c['coupon_serial_number']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= Security::e($c['fuel_type_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold"><?= number_format($c['coupon_value'], 2) ?> <?= $c['value_type'] == 'liters' ? 'L' : 'MWK' ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?= $c['status'] == 'available' ? 'bg-green-100 text-green-800' : 
                                   ($c['status'] == 'issued' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                <?= ucfirst($c['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= $c['issued_date'] ? date('d M Y', strtotime($c['issued_date'])) : '-' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
