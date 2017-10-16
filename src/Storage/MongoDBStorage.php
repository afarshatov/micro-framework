<?php

namespace Lib\Storage;

use Lib\Storage;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use MongoDB\Model\BSONDocument;

class MongoDBStorage extends Storage
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'localhost';

    /**
     * @var int
     */
    const DEFAULT_PORT = 27017;

    /**
     * @var string
     */
    const DEFAULT_PROTOCOL = 'mongodb';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Database;
     */
    protected $database;

    /**
     * @var Collection;
     */
    protected $collection;

    /**
     * MongoDB constructor.
     * @param array $config
     *      string host
     *      int port
     *      string protocol
     */
    public function __construct(array $config = [])
    {
        $host = isset($config['host']) ? $config['host'] : self::DEFAULT_HOST;
        $port = isset($config['port']) ? $config['port'] : self::DEFAULT_PORT;
        $protocol = isset($config['protocol']) ? $config['protocol'] : self::DEFAULT_PROTOCOL;
        $this->client = new Client("{$protocol}://{$host}:{$port}");
    }

    /**
     * @param array $values
     * @return mixed
     */
    public function insert(array $values)
    {
        $result = $this->collection->insertOne($values);

        return $result->getInsertedId();
    }

    /**
     * @param array $where
     * @param array $values
     * @return bool
     */
    public function update(array $where, array $values)
    {
        $result = $this->collection->findOneAndDelete($where, $values);

        return !is_null($result);
    }

    /**
     * @param array $where
     * @return array|null
     */
    public function find(array $where)
    {
        return $this->collection->findOne($where);
    }

    /**
     * @param string $field
     * @return int
     */
    public function findMax($field)
    {
        $data = $this->collection->aggregate([[
            '$group' => [
                '_id' => $field,
                'max' => [
                    '$max' => '$' . $field,
                ]
            ]
        ]]);

        $maxId = 0;
        foreach ($data as $row) {
            /** @var BSONDocument $row */
            $row = $row->getArrayCopy();
            $maxId = $row['max'];
        }

        return $maxId;
    }

    /**
     * @param string $database
     * @return void
     */
    public function setDatabase($database)
    {
        $this->database = $this->client->{$database};
    }

    /**
     * @param string $collection
     * @return void
     */
    public function setCollection($collection)
    {
        $this->collection = $this->database->{$collection};
    }
}