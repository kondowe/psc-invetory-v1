<?php
/**
 * Front Controller
 *
 * Entry point for all requests
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration and constants
require_once __DIR__ . '/config/constants.php';

// Load core classes
require_once __DIR__ . '/core/Logger.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/RBAC.php';

// Load models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Role.php';
require_once __DIR__ . '/models/Permission.php';
require_once __DIR__ . '/models/Department.php';
require_once __DIR__ . '/models/Item.php';
require_once __DIR__ . '/models/ItemCategory.php';
require_once __DIR__ . '/models/UnitOfMeasure.php';
require_once __DIR__ . '/models/Store.php';
require_once __DIR__ . '/models/Supplier.php';
require_once __DIR__ . '/models/StockLevel.php';
require_once __DIR__ . '/models/StockMovement.php';
require_once __DIR__ . '/models/GoodsReceivedVoucher.php';
require_once __DIR__ . '/models/GRVItem.php';
require_once __DIR__ . '/models/WorkflowTemplate.php';
require_once __DIR__ . '/models/WorkflowStep.php';
require_once __DIR__ . '/models/WorkflowInstance.php';
require_once __DIR__ . '/models/WorkflowStepInstance.php';
require_once __DIR__ . '/models/Request.php';
require_once __DIR__ . '/models/RequestItem.php';
require_once __DIR__ . '/models/IssueVoucher.php';
require_once __DIR__ . '/models/IssueVoucherItem.php';
require_once __DIR__ . '/models/Vehicle.php';
require_once __DIR__ . '/models/FuelCoupon.php';

// Start session
Session::start();

try {
    // Parse URL
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // Handle subdirectory installations
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $scriptDir = dirname($scriptName);
    
    // Normalize path
    $path = str_replace($scriptDir, '', $requestUri);
    $path = parse_url($path, PHP_URL_PATH);
    $path = trim($path, '/');

    // Fallback: if the first part of the path IS the scriptDir (without /), strip it
    // This handles cases where str_replace might miss due to trailing slashes
    $dirName = trim($scriptDir, '/');
    if (!empty($dirName) && strpos($path, $dirName . '/') === 0) {
        $path = substr($path, strlen($dirName) + 1);
    } else if ($path === $dirName) {
        $path = '';
    }

    // Default route
    if (empty($path)) {
        $path = 'dashboard';
    }

    // Split path into parts
    $parts = explode('/', $path);
    $controller = $parts[0] ?? 'dashboard';
    $action = $parts[1] ?? 'index';
    $params = array_slice($parts, 2);

    // Map controller names to file names
    $controllerMap = [
        'auth' => 'AuthController',
        'dashboard' => 'DashboardController',
        'requests' => 'RequestController',
        'grv' => 'GRVController',
        'issue' => 'IssueController',
        'fuel' => 'FuelController',
        'inventory' => 'ItemController',
        'item' => 'ItemController',
        'supplier' => 'SupplierController',
        'categories' => 'CategoryController',
        'workflow' => 'WorkflowController',
        'reports' => 'ReportController',
        'users' => 'UserController',
        'departments' => 'DepartmentController',
        'roles' => 'RoleController',
        'audit' => 'AuditController',
        'sla' => 'SlaController',
        'request-management' => 'RequestManagementController'
    ];

    // Get controller class name
    $controllerClass = $controllerMap[$controller] ?? null;

    if (!$controllerClass) {
        Response::notFound('Page not found');
    }

    // Load controller file
    $controllerFile = __DIR__ . "/controllers/{$controllerClass}.php";

    if (!file_exists($controllerFile)) {
        Response::notFound('Controller not found');
    }

    require_once $controllerFile;

    // Instantiate controller
    if (!class_exists($controllerClass)) {
        Response::serverError('Controller class not found');
    }

    $controllerInstance = new $controllerClass();

    // Map URL action to method name (handle hyphens)
    $methodName = str_replace('-', '', lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action)))));

    // Special handling for do-login -> doLogin
    if ($action === 'do-login') {
        $methodName = 'doLogin';
    }

    // Check if method exists
    if (!method_exists($controllerInstance, $methodName)) {
        // Try 'index' method as fallback
        if (method_exists($controllerInstance, 'index')) {
            $methodName = 'index';
            // Add action back to params
            array_unshift($params, $action);
        } else {
            Response::notFound('Action not found');
        }
    }

    // Call controller method
    call_user_func_array([$controllerInstance, $methodName], $params);

} catch (Exception $e) {
    // Log error
    Logger::error('Application error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());

    // Show error (in production, show generic error page)
    if ($_SERVER['SERVER_NAME'] === 'localhost' || getenv('APP_ENV') === 'development') {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        Response::serverError('An error occurred. Please try again later.');
    }
}
