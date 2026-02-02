<?php
/**
 * Logger Class
 *
 * Simple file-based logging utility
 */

class Logger
{
    /**
     * Log levels
     */
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';
    const CRITICAL = 'CRITICAL';

    /**
     * Log file path
     */
    private static $logFile = null;

    /**
     * Error log file path
     */
    private static $errorLogFile = null;

    /**
     * Initialize logger
     */
    private static function init()
    {
        if (self::$logFile === null) {
            $config = require __DIR__ . '/../config/app.php';
            self::$logFile = $config['log_file'];
            self::$errorLogFile = $config['error_log_file'];

            // Ensure log directory exists
            $logDir = dirname(self::$logFile);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
        }
    }

    /**
     * Write log message
     *
     * @param string $level Log level
     * @param string $message Log message
     * @param string $logFile Optional specific log file
     */
    private static function write($level, $message, $logFile = null)
    {
        self::init();

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

        $file = $logFile ?: self::$logFile;

        // Append to log file
        file_put_contents($file, $logMessage, FILE_APPEND);
    }

    /**
     * Log debug message
     *
     * @param string $message Message
     */
    public static function debug($message)
    {
        $config = require __DIR__ . '/../config/app.php';
        if ($config['debug']) {
            self::write(self::DEBUG, $message);
        }
    }

    /**
     * Log info message
     *
     * @param string $message Message
     */
    public static function info($message)
    {
        self::write(self::INFO, $message);
    }

    /**
     * Log warning message
     *
     * @param string $message Message
     */
    public static function warning($message)
    {
        self::write(self::WARNING, $message);
    }

    /**
     * Log error message
     *
     * @param string $message Message
     */
    public static function error($message)
    {
        self::write(self::ERROR, $message, self::$errorLogFile);
    }

    /**
     * Log critical message
     *
     * @param string $message Message
     */
    public static function critical($message)
    {
        self::write(self::CRITICAL, $message, self::$errorLogFile);
    }

    /**
     * Log activity
     *
     * @param int $userId User ID
     * @param string $activityType Activity type
     * @param string $description Description
     */
    public static function logActivity($userId, $activityType, $description)
    {
        $message = "User ID: {$userId} | Activity: {$activityType} | {$description}";
        self::info($message);

        // Also log to database if Database is available
        try {
            if (class_exists('Database')) {
                Database::insert('activity_logs', [
                    'user_id' => $userId,
                    'activity_type' => $activityType,
                    'description' => $description,
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        } catch (Exception $e) {
            // Silently fail if database logging fails
            self::error('Failed to log activity to database: ' . $e->getMessage());
        }
    }
}
