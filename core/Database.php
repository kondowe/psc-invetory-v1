<?php
/**
 * Database Class
 *
 * PDO Singleton for database connections with transaction support
 */

class Database
{
    private static $instance = null;
    private $connection = null;
    private $inTransaction = false;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            Logger::error('Database connection failed: ' . $e->getMessage());
            throw new Exception('Database connection failed. Please contact support.');
        }
    }

    /**
     * Get singleton instance
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get PDO connection
     *
     * @return PDO
     */
    public static function getConnection()
    {
        return self::getInstance()->connection;
    }

    /**
     * Execute a query with parameters
     *
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return PDOStatement
     */
    public static function query($sql, $params = [])
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            Logger::error('Query failed: ' . $e->getMessage() . ' | SQL: ' . $sql . ' | Params: ' . json_encode($params));
            throw new Exception('Database query failed: ' . $e->getMessage());
        }
    }

    /**
     * Fetch all rows
     *
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array
     */
    public static function fetchAll($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single row
     *
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false
     */
    public static function fetchOne($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Insert record
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int Last insert ID
     */
    public static function insert($table, $data)
    {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');

        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        self::query($sql, array_values($data));

        return self::getConnection()->lastInsertId();
    }

    /**
     * Update record
     *
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause (e.g., "id = ?")
     * @param array $whereParams WHERE parameters
     * @return int Number of affected rows
     */
    public static function update($table, $data, $where, $whereParams = [])
    {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = ?";
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";

        $params = array_merge(array_values($data), $whereParams);
        $stmt = self::query($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * Delete record (soft delete recommended - use update instead)
     *
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $whereParams WHERE parameters
     * @return int Number of affected rows
     */
    public static function delete($table, $where, $whereParams = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::query($sql, $whereParams);
        return $stmt->rowCount();
    }

    /**
     * Soft delete (set deleted_at timestamp)
     *
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $whereParams WHERE parameters
     * @return int Number of affected rows
     */
    public static function softDelete($table, $where, $whereParams = [])
    {
        return self::update($table, ['deleted_at' => date('Y-m-d H:i:s')], $where, $whereParams);
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction()
    {
        $instance = self::getInstance();
        if (!$instance->inTransaction) {
            self::getConnection()->beginTransaction();
            $instance->inTransaction = true;
        }
    }

    /**
     * Commit transaction
     */
    public static function commit()
    {
        $instance = self::getInstance();
        if ($instance->inTransaction) {
            self::getConnection()->commit();
            $instance->inTransaction = false;
        }
    }

    /**
     * Rollback transaction
     */
    public static function rollback()
    {
        $instance = self::getInstance();
        if ($instance->inTransaction) {
            self::getConnection()->rollBack();
            $instance->inTransaction = false;
        }
    }

    /**
     * Check if currently in a transaction
     *
     * @return bool
     */
    public static function inTransaction()
    {
        return self::getInstance()->inTransaction;
    }

    /**
     * Prevent cloning
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }
}
