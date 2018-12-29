<?php

namespace App\Storage;

use App\Storage\FileHandle;
use App\Classes\Helper;

/**
 * Can be separate for each Model depending upon requirement. 
 * This way each model could provide static function that return their desired factory that can be passed around and used by factory user function depending upon their requirement
 */
class FileHandleFactory implements FileHandleFactoryContract {
    const OPTION_EXTENSION = 'extension';
    protected $provider;
    protected $baseDir;

    public function __construct($baseDir = '', $prefix = PathHelper::FOLDER_UPLOADS)
    {
        $this->provider = FileHandle::PROVIDER_DEFAULT;
        $this->baseDir = Helper::fixUri($prefix.'/'.$baseDir);
        if($this->baseDir[0] === '/') {
            $this->baseDir = str_replace_first('/','',$this->baseDir);
        }
    }

    protected function setProvider($provider) {
        $this->provider = $provider;
    }

    protected function getRelPath($filename) {
        return PathHelper::getPath($this->baseDir,$filename);
    }

    /**
     * @param string $relPath
     * @return FileHandle
     */
    public function forExisting($relPath, $options = []) {
        return new CrossFileHandle($this->provider, $relPath);
    }

    public function forNew($options = []) {
        return new CrossFileHandle($this->provider, 
            $this->getRelPath(PathHelper::random(
                Helper::getKeyValue($options,self::OPTION_EXTENSION,'')
            ))
        );
    }
}