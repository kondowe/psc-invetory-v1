<?php
/**
 * Session Class
 *
 * Secure session management with timeout and regeneration
 */

class Session
{
    private static $started = false;
    private static $timeout = 3600; // 1 hour

    /**
     * Start session with secure configuration
     */
    public static function start()
    {
        if (self::$started) {
            return;
        }

        $config = require __DIR__ . '/../config/app.php';
        self::$timeout = $config['session']['lifetime'];

        // Secure session configuration
        ini_set('session.cookie_httponly', $config['session']['cookie_httponly'] ? '1' : '0');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_secure', $config['session']['cookie_secure'] ? '1' : '0');
        ini_set('session.cookie_samesite', $config['session']['cookie_samesite']);
        ini_set('session.use_only_cookies', '1');

        session_name($config['session']['name']);
        session_start();

        self::$started = true;

        // Regenerate session ID periodically (every 5 minutes)
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) {
            self::regenerate();
        }

        // Check session timeout
        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > self::$timeout) {
                self::destroy();
                // Redirect will be handled by the caller
                return false;
            }
        }

        $_SESSION['last_activity'] = time();

        return true;
    }

    /**
     * Set session value
     *
     * @param string $key Key
     * @param mixed $value Value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     *
     * @param string $key Key
     * @param mixed $default Default value
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     *
     * @param string $key Key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session value
     *
     * @param string $key Key
     */
    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate()
    {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }

    /**
     * Destroy session
     */
    public static function destroy()
    {
        if (self::$started) {
            // Remove session from database if user is logged in
            if (self::has(SESSION_USER_ID)) {
                try {
                    Database::delete('sessions', 'session_id = ?', [session_id()]);
                } catch (Exception $e) {
                    // Silently fail
                }
            }

            $_SESSION = [];

            // Delete session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();
            self::$started = false;
        }
    }

    /**
     * Flash message - set a message for the next request
     *
     * @param string $key Key
     * @param mixed $value Value
     */
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Get flash message and remove it
     *
     * @param string $key Key
     * @param mixed $default Default value
     * @return mixed
     */
    public static function getFlash($key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }
        return $value;
    }

    /**
     * Check if flash message exists
     *
     * @param string $key Key
     * @return bool
     */
    public static function hasFlash($key)
    {
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Get all flash messages
     *
     * @return array
     */
    public static function getAllFlash()
    {
        $flash = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
        return $flash;
    }

    /**
     * Clear all flash messages
     */
    public static function clearFlash()
    {
        $_SESSION['_flash'] = [];
    }
}
