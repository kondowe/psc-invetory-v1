<?php
/**
 * Response Class
 *
 * JSON and HTML response helpers
 */

class Response
{
    /**
     * Send JSON response
     *
     * @param array $data Response data
     * @param int $statusCode HTTP status code
     */
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo Security::jsonEncode($data);
        exit;
    }

    /**
     * Success JSON response
     *
     * @param mixed $data Data
     * @param string $message Message
     * @param int $statusCode HTTP status code
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Error JSON response
     *
     * @param string $message Error message
     * @param mixed $errors Errors
     * @param int $statusCode HTTP status code
     */
    public static function error($message = 'Error', $errors = null, $statusCode = 400)
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Validation error response
     *
     * @param array $errors Validation errors
     */
    public static function validationError($errors)
    {
        self::error('Validation failed', $errors, 422);
    }

    /**
     * Not found response
     *
     * @param string $message Message
     */
    public static function notFound($message = 'Resource not found')
    {
        self::error($message, null, 404);
    }

    /**
     * Forbidden response
     *
     * @param string $message Message
     */
    public static function forbidden($message = 'Access forbidden')
    {
        self::error($message, null, 403);
    }

    /**
     * Unauthorized response
     *
     * @param string $message Message
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        self::error($message, null, 401);
    }

    /**
     * Server error response
     *
     * @param string $message Message
     */
    public static function serverError($message = 'Internal server error')
    {
        self::error($message, null, 500);
    }

    /**
     * Redirect to URL
     *
     * @param string $url URL
     * @param int $statusCode Status code (301, 302)
     */
    public static function redirect($url, $statusCode = 302)
    {
        // Prepend base path if it's a local redirect (starts with /)
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $scriptName = $_SERVER['SCRIPT_NAME'];
            $baseDir = rtrim(dirname($scriptName), '/\\');
            
            // Avoid double prepending if baseDir is already at the start of url
            if (empty($baseDir) || strpos($url, $baseDir . '/') !== 0) {
                $url = $baseDir . $url;
            }
        }

        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back (to previous page or default)
     *
     * @param string $default Default URL
     */
    public static function back($default = '/')
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? $default;
        self::redirect($referer);
    }

    /**
     * Render view with layout
     *
     * @param string $view View file path (relative to views/)
     * @param array $data Data to pass to view
     * @param string $layout Layout file (default: main)
     */
    public static function view($view, $data = [], $layout = 'main')
    {
        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include view file
        $viewFile = __DIR__ . "/../views/{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View not found: {$view}");
        }

        // Get view content
        $content = ob_get_clean();

        // Include layout
        if ($layout) {
            $layoutFile = __DIR__ . "/../views/layouts/{$layout}.php";
            if (file_exists($layoutFile)) {
                include $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }

        exit;
    }

    /**
     * Render partial view (without layout)
     *
     * @param string $view View file path
     * @param array $data Data
     */
    public static function partial($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("Partial view not found: {$view}");
        }
    }

    /**
     * Download file
     *
     * @param string $filepath File path
     * @param string $filename Download filename
     * @param string $mimeType MIME type
     */
    public static function download($filepath, $filename = null, $mimeType = null)
    {
        if (!file_exists($filepath)) {
            self::notFound('File not found');
        }

        $filename = $filename ?: basename($filepath);
        $mimeType = $mimeType ?: mime_content_type($filepath);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        readfile($filepath);
        exit;
    }

    /**
     * Set HTTP status code
     *
     * @param int $code Status code
     */
    public static function setStatusCode($code)
    {
        http_response_code($code);
    }

    /**
     * Set header
     *
     * @param string $name Header name
     * @param string $value Header value
     */
    public static function setHeader($name, $value)
    {
        header("{$name}: {$value}");
    }
}
