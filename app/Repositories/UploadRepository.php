<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Upload;
use App\Classes\Helper;
use App\Storage\FileHandle;
use App\Storage\PathHelper;
use App\Classes\AppResponse;
use App\Storage\FileHandleFactory;
use App\Storage\FileHandleFactoryContract;
use Illuminate\Contracts\Auth\Authenticatable;

class UploadRepository extends BaseRepository
{
    /**
     * @var FileHandleFactoryContract
     */
    protected $handleFactory;

    /**
     * @param FileHandleFactoryContract $handleFactory
     */
    public function __construct($handleFactory = null)
    {
        if($handleFactory === null) {
            $handleFactory = new FileHandleFactory();
        }
        $this->handleFactory = $handleFactory;
    }

    public function getModel() {
        return Upload::class;
    }

    public function getModelQueryBuilder($purpose = null, $queryOptions = null) {
        return Upload::query();
    }

    /**
     *
     * @param string|null $originalName
     * @param string $relPath
     * @param Authenticatable|null $user
     * @return AppResponse
     */
    public function create($originalName, $relPath = null, $user = null) {
        $info = pathinfo($relPath);
        if(!isset(pathinfo($originalName)['extension'])){
            $originalName = $originalName.'.'.$info['extension'];
        }

        $meta = [
            Upload::META_ORIGINAL_NAME=>$originalName,
            Upload::META_STORED_EXT=>$info['extension'],
            Upload::META_STORED_NAME=>$info['basename'],
            Upload::META_STORED_NAME_WITHOUT_EXT=>$info['filename']
        ];

        return parent::create([
            'owner_id'=>Helper::defaultOnEmptyKey($user,'id'),
            'rel_path'=>$relPath,
            'uploaded_at'=>Carbon::now()->getTimestamp(),
            'metadata'=>json_encode($meta)
        ]);
    }

    /**
     * @param $extension
     * @param $originalName
     * @param $content
     * @param $user
     * @param $relPath
     * @return AppResponse
     */
    protected function storeFile($extension, $originalName, $content, $user, $relPath){
        $extension = strtolower($extension);
        $handle = $this->handleFactory->forNew([
            FileHandleFactory::OPTION_EXTENSION=>$extension
        ]);

        $resp = $this->create($originalName, $handle->getRelPath(), $user);
        if($resp->getStatus()) {
            /**
             * @var Upload $m
             */
            $m = $resp->data;
            $handle->saveContent($content);
        }
        return $resp;
    }

    public function bulkDelete($uploadIds) {
        Upload::in('id',$uploadIds)->delete();
    }

    /**
     * @param [type] $files
     * @param [type] $user
     * @param string $relPath
     * @return AppResponse
     */
    public function uploadFiles($files, $user, $relPath = 'uploads'){
        $resp = new AppResponse(true);
        if(empty($relPath)){
            $relPath = 'uploads';
        }

        $mods = [];

        if(!empty($files)) {
            foreach ($files as $file) {
                $extension = $file->extension();
                $content = file_get_contents($file->path());
                $originalName = $file->getClientOriginalName();
                $r = $this->storeFile($extension,$originalName,$content,$user,$relPath);
                if($r->getStatus()) {
                    $mods[] = $r->data;
                } else {
                    $resp->mergeErrors($r->errors);
                }
            }

            if(count($mods)>0){
                $resp->data = $mods;
            }
        }else{
            $resp->addError('file','Please select files to upload');
        }

        return $resp;
    }

    /**
     * @param mixed $kind anything. This function can be modified to handle kind e.g. to use different FileHandleFactory for each kind
     * @param array $fileInfos (example 'file_infos'  = [{identifier:'XwdF3',anyotherdata:1,anyotherdata:2}])
     * @param array $files (example ['XwdF3'=>Laravel Standard Uploaded file])
     * @param [type] $user
     * @return AppResponse ($data = [identifier from $file_infoes => ['upload_rel'=>$rel_path,'rel_path'=>$rel_path]])
     */
    public function bulkUpload($kind, $fileInfos, $files, $user = null) {
        $resp = new AppResponse(true);
        $toRetData = [];
        $kiosksMap = [];

        foreach ($fileInfos as $info) {
            $factory = new FileHandleFactory();

            $identifier = $info['identifier'];
            $file = Helper::getKeyValue($files,$identifier);
            if($file) {
                $r = $this->uploadFiles([$file],$user);
                $resp->mergeErrors($r->errors);
                $rel_path = null;
                if($r->getStatus()) {
                    $rel_path = $r->data[0];
                }
                
                $toRetData[$identifier] = $rel_path;
                $toRetData[$identifier]['upload_rel'] = $toRetData[$identifier]['rel_path'];
            }
        }
        $resp->data = $toRetData;
        return $resp;
    }

    protected function pngFileContentFromBase64Url($base64Url){
        list($type, $base64Url) = explode(';', $base64Url);
        list(, $base64Url)      = explode(',', $base64Url);
        $base64Url = base64_decode($base64Url);
        return $base64Url;
    }

    /**
     * @param [type] $files
     * @param [type] $user
     * @param string $relPath
     * @return AppResponse
     */
    public function uploadFilesBase64($files, $user, $relPath = 'uploads'){
        $resp = new AppResponse(true);
        if(empty($relPath)) {
            $relPath = 'uploads';
        }
        $mods = [];

        if(!empty($files)){
            foreach ($files as $file) {
                $file = json_decode($file,true);
                $extension = "png";
                $base64Url = $file['base64Url'];
                $content = $this->pngFileContentFromBase64Url($base64Url);
                $originalName = $file['name'];
                $r = $this->storeFile($extension,$originalName,$content,$user,$relPath);
                if($r->getStatus()) {
                    $mods[] = $r->data;
                } else {
                    $resp->mergeErrors($r->errors);
                }
            }

            if(count($mods)>0){
                $resp->data = $mods;
            }
        }else{
            $resp->addError('file','Please select files to upload');
        }

        return $resp;
    }
}