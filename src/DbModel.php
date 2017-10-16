<?php

namespace Lib;

use Exception;
use MongoDB\Model\BSONDocument;

/**
 * Class DbModel
 * @package Lib
 */
abstract class DbModel
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var int|null
     */
    protected $createdAt;

    /**
     * @var int|null
     */
    protected $updatedAt;

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var array
     */
    protected static $fieldsForInsert;

    /**
     * @var array
     */
    protected static $fieldsForUpdate;

    /**
     * @var bool
     */
    protected $isLoaded;

    /**
     * @var string
     */
    public static $database;

    /**
     * @var string
     */
    public static $collection;

    /**
     * DbModel constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        if (empty(static::$database) || empty(static::$collection)) {
            throw new Exception('No database selected in DbModel');
        }

        $this->id = !empty($params['id']) ? $params['id'] : null;
        $this->storage = App::getInstance()->getStorage(static::$database, static::$collection);
        $this->isLoaded = false;
    }

    /**
     * @return DbModel|false
     */
    public function save()
    {
        if ($this->isLoaded) {
            $result = $this->update();
        } else {
            $result = $this->insert();
        }

        return $result ? $this : false;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function insert()
    {
        if (empty(static::$fieldsForInsert)) {
            throw new Exception('There are no fields for insert in db model');
        }

        $this->createdAt = time();
        $values = self::getValuesForSave(array_merge(static::$fieldsForInsert, ['created_at']));

        return $this->storage->insert($values);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function update()
    {
        if (empty(static::$fieldsForUpdate)) {
            throw new Exception('There are no fields for update in db model');
        }

        $this->updatedAt = time();
        $values = self::getValuesForSave(array_merge(static::$fieldsForUpdate, ['updated_at']));

        return $this->storage->update(['id' => $this->id], $values);
    }

    /**
     * @param array $where
     * @return DbModel
     */
    public function find(array $where)
    {
        /** @var BSONDocument $data */
        $data = $this->storage->find($where);

        if (!empty($data)) {
            $data = $data->getArrayCopy();

            foreach ($data as $field => $value) {
                $this->{self::getModelFieldKey($field)} = $value;
            }

            $this->isLoaded = true;
        }

        return $this;
    }

    /**
     * @param string $field
     * @return int
     */
    public function findMax($field)
    {
        return $this->storage->findMax($field);
    }

    /**
     * @return bool
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * @param array $fieldsByOperation - static::$fieldsForInsert or static::$fieldsForUpdate
     * @return array
     */
    protected function getValuesForSave(array $fieldsByOperation)
    {
        $values = [];

        foreach ($fieldsByOperation as $field) {
            $values[$field] = $this->{self::getModelFieldKey($field)};
        }

        return $values;
    }

    /**
     * @param $dbKey
     * @return string
     */
    protected static function getModelFieldKey($dbKey)
    {
        return str_replace('_', '', lcfirst(ucwords($dbKey, '_')));
    }
}