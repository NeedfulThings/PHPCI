<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Logging;

use b8\Store;
use Monolog\Handler\AbstractProcessingHandler;
use PHPCI\Model\Build;

class BuildDBLogHandler extends AbstractProcessingHandler
{
    /**
     * @var Build
     */
    protected $build;

    protected $logValue;

    public function __construct(
        Build $build,
        $level = LogLevel::INFO,
        $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->build = $build;
        // We want to add to any existing saved log information.
        $this->logValue = $build->getLog();
    }

    protected function write(array $record)
    {
        $message = (string)$record['message'];
        $message = str_replace($this->build->currentBuildPath, '/', $message);

        $this->logValue .= $message . PHP_EOL;
        $this->build->setLog($this->logValue);
    }
}
