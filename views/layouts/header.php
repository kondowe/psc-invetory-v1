<?php
$user = Auth::user();
?>
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center">
            <!-- Mobile Menu Toggle -->
            <button onclick="toggleSidebar()" class="mr-4 lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-md focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <h1 class="text-xl md:text-2xl font-semibold text-gray-800">
                <?= isset($pageTitle) ? Security::e($pageTitle) : 'Dashboard' ?>
            </h1>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Notifications (placeholder) -->
            <div class="relative">
                <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">0</span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button onclick="toggleUserMenu()" class="flex items-center space-x-3 focus:outline-none">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold">
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    </div>
                    <div class="text-left hidden md:block">
                        <div class="text-sm font-medium text-gray-900"><?= Security::e($user['full_name']) ?></div>
                        <div class="text-xs text-gray-500"><?= Security::e($user['role_name']) ?></div>
                    </div>
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                    <a href="<?= Security::url('/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </a>
                    <a href="<?= Security::url('/settings') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Settings
                    </a>
                    <hr class="my-1">
                    <a href="<?= Security::url('/auth/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('hidden');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const target = event.target;

    if (!target.closest('button[onclick="toggleUserMenu()"]') && !target.closest('#userMenu')) {
        userMenu.classList.add('hidden');
    }
});
</script>
