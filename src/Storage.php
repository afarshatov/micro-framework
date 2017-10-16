<?php

namespace Lib;

use Exception;

abstract class Storage
{
    /**
     * @var string
     */
    const STORAGE_FILE = 'storage';

    /**
     * @var Storage
     */
    private static $instance;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $collection;

    /**
     * @param string $storageFile
     * @param string $storageDir
     * @return Storage
     */
    public static function getInstance($storageFile = self::STORAGE_FILE, $storageDir = App::CONFIGS_DIR)
    {
        if (null === self::$instance) {
            $config = ConfigFile::load($storageFile, $storageDir);
            $className = $config['type'];
            self::$instance = new $className($config);
        }

        return self::$instance;
    }

    /**
     * Storage constructor.
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    protected function __construct($host, $port, $protocol) {}

    /**
     * @param array $params
     * @return mixed
     */
    public abstract function find(array $params);

    /**
     * @param array $values
     * @return mixed
     */
    public abstract function insert(array $values);

    /**
     * @param array $where
     * @param array $values
     * @return mixed
     */
    public abstract function update(array $where, array $values);

    /**
     * @param string $field
     * @return mixed
     */
    public abstract function findMax($field);

    /**
     * @param string $database
     * @return void
     */
    public function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * @param string $collection
     * @return void
     */
    public function setCollection($collection) {
        $this->collection = $collection;
    }
}