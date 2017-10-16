<?php

namespace Lib;

class Repository
{
    /**
     * @var DbModel
     */
    protected static $model;

    /**
     * @param array $row
     * @return DbModel|null
     */
    protected function prepareRowOutput($row)
    {
        if (empty($row)) {
            return null;
        }

        return new static::$model($row);
    }

    /**
     * @param array $list
     * @return array[] DbModel
     */
    protected function prepareListOutput($list)
    {
        if (empty($list)) {
            return [];
        }

        $output = [];

        foreach ($list as $row) {
            $output[] = $this->prepareRowOutput($row);
        }

       return $output;
    }
}