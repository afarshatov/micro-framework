<?php

namespace Lib;

use Exception;

class ConfigFile
{
    const EXTENSION_PHP = '.php';

    /**
     * @param string $file
     * @param string $dir
     * @param string $extension
     * @return array
     * @throws Exception
     */
    public static function load($file, $dir, $extension = self::EXTENSION_PHP)
    {
        $filePath = self::getFile($file, $dir, $extension);
        $config = null;

        switch ($extension) {
            case self::EXTENSION_PHP:
                $config = require_once($filePath);
        }

        if (!is_array($config)) {
            throw new Exception('Translations file found but cant get values from it');
        }

        return $config;
    }

    /**
     * Check if file exists and is readable
     * @param string $file
     * @param string $dir
     * @param string $extension
     * @return string
     * @throws Exception
     */
    protected static function getFile($file, $dir, $extension)
    {
        $file = self::getFileFullPath($file, $dir, $extension);

        if (!file_exists($file) || !is_readable($file)) {
            throw new Exception("Can not load locale file: {$file}");
        }

        return $file;
    }

    /**
     * @param string $file
     * @param string $dir
     * @param string $extension
     * @return string
     */
    protected static function getFileFullPath($file, $dir, $extension)
    {
        $app = App::getInstance();

        return $app->getBaseDir() . DIRECTORY_SEPARATOR .
            $dir . DIRECTORY_SEPARATOR .
            $file . $extension;
    }
}