<?php
/**
 * Security Class
 *
 * CSRF tokens, XSS prevention, input sanitization
 */

class Security
{
    /**
     * Generate CSRF token
     *
     * @return string
     */
    public static function generateCsrfToken()
    {
        if (!Session::has(SESSION_CSRF_TOKEN)) {
            Session::set(SESSION_CSRF_TOKEN, bin2hex(random_bytes(32)));
        }
        return Session::get(SESSION_CSRF_TOKEN);
    }

    /**
     * Validate CSRF token
     *
     * @param string $token Token to validate
     * @return bool
     */
    public static function validateCsrfToken($token = null)
    {
        $sessionToken = Session::get(SESSION_CSRF_TOKEN);

        if ($token === null) {
            $token = $_POST[SESSION_CSRF_TOKEN] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }

        return hash_equals($sessionToken, $token);
    }

    /**
     * Check CSRF token and throw exception if invalid
     * Call this on all POST requests
     */
    public static function checkCsrfToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!self::validateCsrfToken()) {
                http_response_code(403);
                Logger::warning('CSRF token validation failed from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                throw new Exception('CSRF token validation failed. Please refresh and try again.');
            }
        }
    }

    /**
     * Get CSRF token HTML input field
     *
     * @return string
     */
    public static function csrfField()
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="' . SESSION_CSRF_TOKEN . '" value="' . $token . '">';
    }

    /**
     * Alias for csrfField()
     *
     * @return string
     */
    public static function csrfInput()
    {
        return self::csrfField();
    }

    /**
     * Get CSRF token for JavaScript
     *
     * @return string
     */
    public static function csrfToken()
    {
        return self::generateCsrfToken();
    }

    /**
     * Escape output for HTML (XSS prevention)
     *
     * @param string $string String to escape
     * @return string
     */
    public static function escape($string)
    {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Generate a URL with the base path
     *
     * @param string $path Path relative to root
     * @return string
     */
    public static function url($path = '')
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $baseDir = rtrim(dirname($scriptName), '/\\');
        
        // Ensure path starts with /
        if ($path !== '' && $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        return $baseDir . $path;
    }

    /**
     * Alias for escape
     */
    public static function e($string)
    {
        return self::escape($string);
    }

    /**
     * Sanitize input
     *
     * @param mixed $input Input to sanitize
     * @param string $type Type of sanitization
     * @return mixed
     */
    public static function sanitize($input, $type = 'string')
    {
        if (is_array($input)) {
            return array_map(function ($item) use ($type) {
                return self::sanitize($item, $type);
            }, $input);
        }

        switch ($type) {
            case 'string':
                return trim(strip_tags($input));

            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);

            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);

            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);

            case 'html':
                // Allow safe HTML tags
                return strip_tags($input, '<p><br><strong><em><u><a><ul><ol><li>');

            default:
                return trim($input);
        }
    }

    /**
     * Clean input recursively (for $_POST, $_GET)
     *
     * @param array $data Data to clean
     * @return array
     */
    public static function cleanInput($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'cleanInput'], $data);
        }
        return self::sanitize($data, 'string');
    }

    /**
     * Hash password
     *
     * @param string $password Plain text password
     * @return string Hashed password
     */
    public static function hashPassword($password)
    {
        $config = require __DIR__ . '/../config/app.php';
        $cost = $config['password_bcrypt_cost'];
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    /**
     * Verify password
     *
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Validate password strength
     *
     * @param string $password Password to validate
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validatePasswordStrength($password)
    {
        $config = require __DIR__ . '/../config/app.php';

        // Check minimum length
        if (strlen($password) < 8) {
            return ['valid' => false, 'message' => 'Password must be at least 8 characters long'];
        }

        // Check complexity (uppercase, lowercase, number)
        if (!preg_match('/[A-Z]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one uppercase letter'];
        }

        if (!preg_match('/[a-z]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one lowercase letter'];
        }

        if (!preg_match('/[0-9]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one number'];
        }

        return ['valid' => true, 'message' => 'Password is strong'];
    }

    /**
     * Generate random token
     *
     * @param int $length Length
     * @return string
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Prevent XSS in JSON output
     *
     * @param mixed $data Data to encode
     * @return string
     */
    public static function jsonEncode($data)
    {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    public static function getClientIp()
    {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return 'unknown';
    }

    /**
     * Get user agent
     *
     * @return string
     */
    public static function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }
}
