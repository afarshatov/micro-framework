<?php

namespace Micro;

use Exception;

class Template
{
    /**
     * @var string
     */
    const TEMPLATES_DIR = 'templates';

    /**
     * @var string
     */
    const TEMPLATE_VAR_REGEXP = '/\{\{\s(.+)\s\}\}/';

    /**
     * @vat string
     */
    const TEMPLATE_LABEL_PLACEHOLDER = 'label:';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @var array
     */
    protected $params;

    /**
     * Template constructor.
     * @param string $path
     * @param array $params
     * @param string $dir
     * @param string|null $locale
     */
    public function __construct($path, array $params = [], $dir = self::TEMPLATES_DIR, $locale = null)
    {
        $this->path = $path;
        $this->params = $params;
        $this->dir = $dir;
        $this->locale = Locale::getInstance($locale);
    }

    /**
     * Renders template with variables
     * @return void
     */
    public function render()
    {
        echo $this->getProcessedTemplate();
    }

    /**
     * Get templates content with variables replaces by values
     * @return string
     * @throws Exception
     */
    protected function getProcessedTemplate()
    {
        $content = $this->getFileContent();
        $processedContent = $this->processContent($content);

        return $processedContent;
    }

    /**
     * Read template file content as string
     * @return string
     * @throws Exception
     */
    protected function getFileContent()
    {
        $file = $this->getFileFullPath();

        if (file_exists($file) && is_readable($file)) {
            return file_get_contents($file);
        }

        throw new Exception('Can not find template ' . $file);
    }

    /**
     * Replace variables by values
     * @param string $content
     * @return string
     */
    protected function processContent($content)
    {
        return preg_replace_callback(
            self::TEMPLATE_VAR_REGEXP,
            [$this, 'getVariableValue'],
            $content
        );
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function getVariableValue($matches)
    {
        $variableKey = $matches[1];

        if ($this->isLabel($variableKey)) {
            return $this->getLabel($variableKey);
        }

        return $this->params[$variableKey];
    }

    /**
     * @param string $variable
     * @return bool
     */
    protected function isLabel($variable)
    {
        return substr($variable, 0, strlen(self::TEMPLATE_LABEL_PLACEHOLDER)) === self::TEMPLATE_LABEL_PLACEHOLDER;
    }

    /**
     * @param $variable
     * @return string
     */
    protected function getLabel($variable)
    {
        $labelKey = substr($variable, strlen(self::TEMPLATE_LABEL_PLACEHOLDER));

        return $this->locale->translate($labelKey);
    }

    /**
     * @return string
     */
    protected function getFileFullPath()
    {
        $app = App::getInstance();

        return $app->getBaseDir() . DIRECTORY_SEPARATOR .
            $this->dir . DIRECTORY_SEPARATOR .
            $this->path;
    }
}