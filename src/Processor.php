<?php

namespace App;

/**
 * Class Processor
 */
class Processor
{
    /**
     * @var array
     */
    private $exporter;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * Processor constructor.
     * @param Exporter $exporter
     * @param string   $rootDir
     */
    public function __construct(
        Exporter $exporter,
        string $rootDir
    ) {
        $this->exporter = $exporter;
        $this->rootDir = $rootDir;
    }

    /**
     * @param string $exportType
     */
    public function start(string $exportType)
    {
        // handle next
        $nextFile = Helper::checkCopiedDist($this->rootDir.'/var/next');

        $status = $this->next(
            $exportType,
            $this->createDataDir($exportType),
            Helper::strictFilter(file_get_contents($nextFile))
        );

        if (true === $status) {
            file_put_contents($nextFile, '');
        } else {
            exit;
        }
    }

    /**
     * @param string      $exportType
     * @param string      $dataDir
     * @param string|null $next
     *
     * @return bool
     */
    private function next(string $exportType, string $dataDir, string $next = null)
    {
        $this->write($exportType, 0);

        $exporterMethod = 'export'.ucfirst($exportType);

        $totalCount = 0;

        do {
            try {
                list($next, $count) = $this->exporter->$exporterMethod($dataDir, $next);
                $totalCount += $count;

                if (true === $next) {
                    // take a break
                    $this->write($exportType, 'SLEEP');

                    sleep(60);
                } elseif (false === $next) {
                    // end reached
                    $this->write($exportType, 'DONE');

                    return true;
                } else {
                    // next
                    $this->write($exportType, $totalCount);

                    file_put_contents($this->rootDir.'/var/next', $next);
                }
            } catch (\Exception $e) {
                // error
                $this->write($exportType, $e->getMessage());
            }

            $continue = file_get_contents($this->rootDir.'/var/continue');
        } while (!empty($continue));

        // stopped
        $this->write($exportType, 'STOP');

        return false;
    }

    /**
     * @param string $dirName
     *
     * @return string
     */
    private function createDataDir(string $dirName)
    {
        $dir = $this->rootDir.'/data/'.$dirName;

        if (!file_exists($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * @param string $exportType
     * @param mixed  $status
     */
    private function write(string $exportType, $status)
    {
        printf("%s: %s\n", $exportType, (string) $status);
    }
}
