<?php

namespace App\Storage;

class CrossFileHandle extends FileHandle
{
    protected $providers;


    public function __construct($sourceProvider, $relPath, $cleanup = false) {
        parent::__construct($sourceProvider,$relPath,$cleanup);
        $this->providers = [
            $this->provider=>[
                $cleanup=>$this
            ]
        ];
    }

    /**
     * @param null $provider null=sourceProvider
     * @param bool $cleanup
     * @param null $newPath
     * @return CrossFileHandle
     */
    public function asProvider($provider, $cleanup = false, $newPath = null) {
        if($provider == null) {
            $provider = $this->provider;
        }

        if(!isset($this->providers[$provider])) {
            $this->providers[$provider] = [];
        }

        if(!isset($this->providers[$provider][$cleanup])) {
            if($newPath === null) {
                $newPath = uniqid('provider_init_').'.'.$this->getExtension();
            }

            $fs = new CrossFileHandle($provider, $newPath, $cleanup);
            $fs->saveContent($this->getContent());
            $this->providers[$provider][$cleanup] = $fs;
        }

        return $this->providers[$provider][$cleanup];
    }
}