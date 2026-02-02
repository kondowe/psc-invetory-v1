<?php $pageTitle = 'Reports Center'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Reports Center</h2>
    <p class="text-gray-600">Select a report to generate.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
        <h3 class="font-bold text-gray-900 mb-2">Inventory Stock Report</h3>
        <p class="text-sm text-gray-500 mb-4">Detailed list of current stock levels across all stores.</p>
        <a href="<?= Security::url('/reports/inventory') ?>" class="text-blue-600 font-medium text-sm hover:underline">Generate Report →</a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition opacity-50 cursor-not-allowed">
        <h3 class="font-bold text-gray-900 mb-2">Request History</h3>
        <p class="text-sm text-gray-500 mb-4">Summary of requisitions within a date range.</p>
        <span class="text-gray-400 text-xs italic">Coming Soon</span>
    </div>

    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition opacity-50 cursor-not-allowed">
        <h3 class="font-bold text-gray-900 mb-2">Fuel Usage Audit</h3>
        <p class="text-sm text-gray-500 mb-4">Audit trail of fuel coupon issuance and vehicle consumption.</p>
        <span class="text-gray-400 text-xs italic">Coming Soon</span>
    </div>
</div>
