<?php
/**
 * SystemConfig Model
 */

require_once __DIR__ . '/BaseModel.php';

class SystemConfig extends BaseModel
{
    protected static $table = 'system_config';
    protected static $primaryKey = 'config_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'config_key',
        'config_value',
        'config_type',
        'description',
        'is_editable'
    ];

    /**
     * Get config value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $config = self::first(['config_key' => $key]);
        if (!$config) {
            return $default;
        }

        $value = $config['config_value'];
        switch ($config['config_type']) {
            case 'number':
                return (float)$value;
            case 'boolean':
                return strtolower($value) === 'true' || $value === '1';
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Set config value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set($key, $value)
    {
        $config = self::first(['config_key' => $key]);
        if (!$config) {
            return false;
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        return self::update($config['config_id'], ['config_value' => (string)$value]);
    }

    /**
     * Get all editable configs
     *
     * @return array
     */
    public static function getEditable()
    {
        return self::where(['is_editable' => 1]);
    }
}
