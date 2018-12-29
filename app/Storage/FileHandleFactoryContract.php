<?php

namespace App\Storage;

use App\Storage\FileHandle;
use App\Classes\Helper;

interface FileHandleFactoryContract {
    /**
     * Should return FileHandle capable of handling existing file in $relPath
     * @param string $relPath
     * @param array $options
     * @return FileHandle
     */
    function forExisting($relPath, $options = []);
    /**
     * Should return FileHandle capable of handling creation of file in $relPath
     * @param array $options
     * @return FileHandle
     */
    function forNew($options = []);
}