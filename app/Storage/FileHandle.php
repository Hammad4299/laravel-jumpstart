<?php

namespace App\Storage;

use App\Classes\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\File;


class FileHandle
{
    const PROVIDER_DEFAULT = 'doesntmatter';
    const PROVIDER_PUBLIC = 'public';
    const PROVIDER_TEMP = 'temp';
    protected $provider;
    protected $relPath;
    protected $toClean;

    /**
     * @var FilesystemAdapter
     */
    protected $client;

    public function __construct($provider, $relPath, $cleanup = false) {
        /**
         * FilesystemAdapter
         */
        $this->provider = $provider;
        $this->client = self::getFileSystem($provider);
        $this->relPath = $relPath;
        $this->toClean = $cleanup;
    }

    /**
     * @return void
     */
    public function closeResource($resource) {
        if(is_resource($resource)) {
            fclose($resource);
        }
    }

    public function __destruct()
    {
        if($this->toClean)
            $this->delete();
    }

    /**
     * useful for relative url based on selected storage driver/provider. e.g. to send download via nginx ACCEL header
     * @return string
     */
    public function getInternalRedirectUrl() {
        $conf = config('filesystems.disks.'.$this->provider);
        $base = Helper::getKeyValue($conf,'internal_redirect_url_base','/storage/');
        return $base.$this->getRelPath();
    }

    public static function getFileSystem($provider) {
        if($provider == self::PROVIDER_DEFAULT){
            return Storage::disk();
        } else {
            return Storage::disk($provider);
        }
    }

    public function createDirectories() {
        $dir = pathinfo($this->getAbsolutePath(),PATHINFO_DIRNAME);
        if(!file_exists($dir)) {
            mkdir($dir);
        }
    }

    /**
     * @return string
     */
    public function getExtension(){
        $info = pathinfo($this->relPath);
        return Helper::getKeyValue($info,'extension');
    }

    /**
     * @return string
     */
    public function getFullUrl(){
        return $this->client->url($this->relPath);
    }

    /**
     * @return string
     */
    public function getRelPath(){
        return $this->relPath;
    }

    /**
     * @param string $newRel
     * @return FileHandle
     */
    public function copy($newRel) {
        $this->client->copy($this->relPath, $newRel);
        return new FileHandle($this->provider, $newRel);
    }

    /**
     * @param string $newRel
     * @return FileHandle
     */
    public function move($newRel) {
        $this->client->move($this->relPath, $newRel);
        return new FileHandle($this->provider, $newRel);
    }

    /**
     * @return FilesystemAdapter
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * @return bool
     */
    public function exists() {
        return $this->client->exists($this->relPath);
    }

    /**
     * @return string
     */
    public function getAbsolutePath() {
        return PathHelper::asAbsolutePath($this->client,$this->relPath);
    }

    public function getDirectoryPath() {
        return pathinfo($this->relPath, PATHINFO_DIRNAME);
    }

    public function getFilenameWithExtension() {
        return pathinfo($this->relPath, PATHINFO_BASENAME);
    }

    /**
     * Warning!!! See this function and comments related to relPath in case $content is File|UploadedFile
     * @param string|resource|File|UploadedFile $content
     * @return bool|string
     */
    public function put($content) {
        $r = false;
        if($content instanceof File || $content instanceof UploadedFile) {    //FilesystemAdapter uses putFile() to store this file, that randomly generates filename. So adjust relPath accordingly
            $r = $this->client->put($this->getDirectoryPath(), $content);
            if(is_string($r)) {
                $this->relPath = $r;
            }
        } else {
            $r = $this->client->put($this->relPath, $content);
        }
        return $r;
    }

    /**
     * @return resource|false
     */
    public function readStream() {
        return $this->client->getDriver()->readStream($this->relPath);
    }

    /**
     * @return string
     */
    public function get() {
        return $this->client->get($this->relPath);
    }

    /**
     * @return bool
     */
    public function delete() {
        $this->client->delete($this->relPath);
    }
}