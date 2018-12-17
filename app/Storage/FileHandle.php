<?php

namespace App\Storage;

use App\Classes\Helper;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

/**
 * Only supports Grant Auth
 * Class DocuSign
 * @package App\Classes
 */
class FileHandle
{
    const PROVIDER_DEFAULT = 'def';
    const PROVIDER_PUBLIC = 'public';
    const PROVIDER_TEMP = 'temp';
    protected $provider;
    protected $relPath;
    protected $toClean;

    /**
     * @var Filesystem
     */
    protected $client;

    public function __destruct()
    {
        if($this->toClean)
            $this->delete();
    }

    public function __construct($provider, $relPath, $cleanup = false) {
        $this->provider = $provider;
        $this->client = self::getFileSystem($provider);
        $this->relPath = $relPath;
        $this->toClean = $cleanup;
    }

    public static function getFileSystem($provider) {
        if($provider == self::PROVIDER_DEFAULT){
            return Storage::disk();
        } else {
            return Storage::disk($provider);
        }
    }

    public function getExtension(){
        $info = pathinfo($this->relPath);
        return Helper::getKeyValue($info,'extension');
    }

    public function getFullUrl(){
        return $this->client->url($this->relPath);
    }

    public function getRelPath(){
        return $this->relPath;
    }

    public function copy($newRel) {
        $this->client->copy($this->relPath,$newRel);
        return new FileHandle($this->provider,$newRel);
    }

    public function move($newRel) {
        $this->client->move($this->relPath,$newRel);
        return new FileHandle($this->provider,$newRel);
    }

    public function getClient() {
        return $this->client;
    }

    public static function asAbsolutePath($filesystem, $relPath) {
        $storagePath  = $filesystem->getDriver()->getAdapter()->getPathPrefix();
        return Helper::fixUri($storagePath."/".$relPath);
    }

    public function exists() {
        return $this->client->exists($this->relPath);
    }

    public function getAbsolutePath() {
        return self::asAbsolutePath($this->client,$this->relPath);
    }

    public function saveContent($content) {
        return $this->client->put($this->relPath, $content);
    }

    public function getContent() {
        return $this->client->get($this->relPath);
    }

    public function delete() {
        $this->client->delete($this->relPath);
    }
}