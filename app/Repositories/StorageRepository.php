<?php

namespace App\Repositories;

use App\Classes\Helper;
use App\Classes\AppResponse;
use App\Storage\FileHandleFactory;
use App\Storage\FileHandleFactoryContract;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use App\DTO\FileDTO;

class StorageRepository
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

    /**
     * @param $extension
     * @param $originalName
     * @param string|resource|File|UploadedFile $content
     * @return AppResponse
     */
    protected function storeFile($extension, $originalName, $content) {
        $extension = strtolower($extension);
        $handle = $this->handleFactory->forNew([
            FileHandleFactory::OPTION_EXTENSION=>$extension
        ]);
        $handle->put($content);
        $dto = new FileDTO();
        $dto->name = $originalName;
        $dto->extension = $extension;
        $dto->upload_rel = $handle->getRelPath();
        $dto->setContract($this->handleFactory);
        $resp = new AppResponse(true, $dto);
        return $resp;
    }

    protected function deleteFiles($relPaths) {
        if($this->handleFactory) {
            foreach($relPaths as $path) {
                if(!empty($path)) {
                    $this->handleFactory->forExisting($path)->delete();
                }
            }
        }
    }

	public function bulkDelete($uploadRelPaths) {
        if($this->handleFactory) {
            $this->deleteFiles($uploadRelPaths);
        }
    }


    /**
     * @param [type] $files
     * @return AppResponse
     */
    public function uploadFiles($files){
        $resp = new AppResponse(true);

        $mods = [];
        if(!empty($files)) {
            $f = Helper::toCollection($files);
            foreach ($f as $file) {
                $extension = !empty($file->extension()) ? $file->extension() : $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $r = $this->storeFile($extension,$originalName,$file);
                if($r->getStatus()) {
                    $mods[] = $r->data;
                } else {
                    $resp->mergeErrors($r->errors);
                }
            }

            if(count($mods)>0){
                if(is_array($files)) {
                    $resp->data = $mods;
                } else {
                    $resp->data = $mods[0];
                }
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
     * @return AppResponse ($data = [identifier from $file_infoes => FileDTO])
     */
    public function bulkUpload($kind, $fileInfos, $files) {
        $resp = new AppResponse(true);
        $toRetData = [];

        foreach ($fileInfos as $info) {
            $factory = new FileHandleFactory();
            $identifier = $info['identifier'];
            $file = Helper::getKeyValue($files,$identifier);
            if($file) {
                $r = $this->uploadFiles($file);
                $resp->mergeErrors($r->errors);
                $rel_path = null;
                $toRetData[$identifier] = null;
                if($r->getStatus()) {
                    $fileDto = $r->data;
                    $toRetData[$identifier] = $fileDto;
                }
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
     * @return AppResponse
     */
    public function uploadFilesBase64($files){
        $resp = new AppResponse(true);
        if(empty($relPath)) {
            $relPath = 'uploads';
        }
        $mods = [];

        if(!empty($files)){
            $d = Helper::toCollection($files);
            foreach ($d as $file) {
                $file = json_decode($file,true);
                $extension = "png";
                $base64Url = $file['base64Url'];
                $content = $this->pngFileContentFromBase64Url($base64Url);
                $originalName = $file['name'];
                $r = $this->storeFile($extension,$originalName,$content);
                if($r->getStatus()) {
                    $mods[] = $r->data;
                } else {
                    $resp->mergeErrors($r->errors);
                }
            }

            if(count($mods)>0){
                if(is_array($files)) {
                    $resp->data = $mods;
                } else {
                    $resp->data = $mods[0];
                }
            }
        }else{
            $resp->addError('file','Please select files to upload');
        }

        return $resp;
    }
}