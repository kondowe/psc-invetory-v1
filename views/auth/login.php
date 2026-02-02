<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System</title>
    <link rel="icon" type="image/png" href="<?= Security::url('/assets/images/logo.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <img src="<?= Security::url('/assets/images/logo.png') ?>" alt="Logo" class="mx-auto h-24 w-24 mb-4">
            <h1 class="text-3xl font-bold text-gray-800">IMS</h1>
            <p class="text-gray-600 mt-2">Inventory Management System</p>
        </div>

        <!-- Flash Messages -->
        <?php
        $successMessage = Session::getFlash('success');
        $errorMessage = Session::getFlash('error');
        ?>

        <?php if ($successMessage): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= Security::e($successMessage) ?>
            </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= Security::e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form action="<?= Security::url('/auth/do-login') ?>" method="POST">
            <?= Security::csrfField() ?>

            <div class="mb-6">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">
                    Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    autofocus
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your username"
                >
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your password"
                >
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                    <span class="ml-2 text-sm text-gray-700">Remember me</span>
                </label>
                <a href="<?= Security::url('/auth/forgot-password') ?>" class="text-sm text-blue-600 hover:text-blue-800">
                    Forgot password?
                </a>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200"
            >
                Login
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">Default credentials:</p>
            <p class="text-xs text-gray-500 mt-1">
                <strong>Username:</strong> admin &nbsp;|&nbsp; <strong>Password:</strong> Admin@123
            </p>
        </div>
    </div>
</body>
</html>
