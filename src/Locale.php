<?php

namespace Lib;

use Exception;

/**
 * Class Locale
 */
class Locale
{

    /**
     * @var Locale
     */
    private static $instance;

    /**
     * @var string
     */
    const LOCALES_DIR = 'locales';

    /**
     * @var string
     */
    const KEY_NEXT_LEVEL_SEPARATOR = '.';

    /**
     * @var array
     */
    protected $translations;

    /**
     * @param $locale
     * @param string $localesDir
     * @param string $localesExtension
     * @return Locale
     */
    public static function getInstance($locale = null, $localesDir = self::LOCALES_DIR, $localesExtension = ConfigFile::EXTENSION_PHP)
    {
        if (null === self::$instance) {
            self::$instance = new self($locale, $localesDir, $localesExtension);
        }

        return self::$instance;
    }

    /**
     * Locale constructor.
     * @param $locale
     * @param string $localesDir
     * @param string $localesExtension
     */
    private function __construct($locale = null, $localesDir = self::LOCALES_DIR, $localesExtension = ConfigFile::EXTENSION_PHP)
    {
        if (is_null($locale)) {
            $locale = App::getInstance()->getLocale();
        }

        $this->translations = ConfigFile::load($locale, $localesDir, $localesExtension);
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Get translation by key
     * @param string $key
     * @return string
     * @throws Exception
     */
    public static function translate($key)
    {
        $translations = self::getInstance()->getTranslations();

        // loading keys like "key1.key2.key3"
        while (($keySeparatorPosition = strpos($key, self::KEY_NEXT_LEVEL_SEPARATOR)) !== false) {
            $subsetKey = substr($key, 0, $keySeparatorPosition);

            if (!isset($translations[$subsetKey])) {
                throw new Exception("Can not load translation by sub key {$subsetKey}, key {$key}");
            }

            $translations = $translations[$subsetKey];
            $key = substr($key, $keySeparatorPosition + 1);
        }

        if (!isset($translations[$key])) {
            throw new Exception("Can not load translation by key {$key}");
        }

        return $translations[$key];
    }
}