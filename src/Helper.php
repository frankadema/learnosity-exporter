<?php

namespace App;

/**
 * Class Helper
 */
class Helper
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public static function checkCopiedDist(string $filename)
    {
        if (!file_exists($filename)) {
            exit("Copy $filename.dist to $filename and provide details.\n");
        }

        return $filename;
    }

    /**
     * @param mixed $val
     *
     * @return mixed|null
     */
    public static function strictFilter($val)
    {
        return empty($val) ? null : $val;
    }
}