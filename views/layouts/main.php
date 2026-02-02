<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? Security::e($pageTitle) . ' - ' : '' ?>Inventory Management System</title>
    <link rel="icon" type="image/png" href="<?= Security::url('/assets/images/logo.png') ?>">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= Security::url('/assets/css/custom.css') ?>">

    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="<?= Security::csrfToken() ?>">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <?php include __DIR__ . '/header.php'; ?>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                <?php
                $successMessage = Session::getFlash('success');
                $errorMessage = Session::getFlash('error');
                $warningMessage = Session::getFlash('warning');
                $infoMessage = Session::getFlash('info');
                ?>

                <?php if ($successMessage): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= Security::e($successMessage) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($errorMessage): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= Security::e($errorMessage) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($warningMessage): ?>
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= Security::e($warningMessage) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($infoMessage): ?>
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?= Security::e($infoMessage) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?= $content ?>
            </main>

            <!-- Footer -->
            <?php include __DIR__ . '/footer.php'; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= Security::url('/assets/js/app.js') ?>"></script>
</body>
</html>
