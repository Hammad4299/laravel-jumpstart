<?php

namespace App\Repositories;

use App\Classes\AppResponse;
use App\Classes\Helper;
use App\Models\Upload;
use App\Storage\CrossFileHandle;
use App\Storage\FileHandle;
use App\Storage\PathHelper;
use Carbon\Carbon;
use App\Repositories\BaseRepository;

class UploadRepository extends BaseRepository
{
    public function createUpload($fromRelPath, $user_id, $original_name) {
        return $this->createFile($original_name, $user_id, $fromRelPath);
    }

    public function get($id){
        $resp = new AppResponse(true);
        $resp->data = Upload::find($id);
        return $resp;
    }

    protected function createFile($originalName, $user_id, $relPath){
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

        return Upload::create([
            'owner_id'=>$user_id,
            'rel_path'=>$relPath,
            'uploaded_at'=>Carbon::now()->getTimestamp(),
            'metadata'=>json_encode($meta)
        ]);
    }

    /**
     * @param $extension
     * @param $originalName
     * @param $content
     * @param $user_id
     * @param $relPath
     * @return array ['model'=>$model,'handle'=>CrossFileHandle] no cleanup
     */
    protected function storeFile($extension, $originalName, $content, $user_id, $relPath){
        $extension = strtolower($extension);
        $fileNameWithoutExtension = md5(uniqid());
        $filename = $fileNameWithoutExtension.'.'.$extension;
        $relPath = $relPath.'/'.$filename;

        $resp = $this->createFile($originalName,$user_id,$relPath,$access);
        $storage = new CrossFileHandle(FileHandle::PROVIDER_DEFAULT, $resp->rel_path);
        $storage->saveContent($content);
        $resp = [
            'model'=>$resp,
            'handle'=>$storage
        ];
        return $resp;
    }

    public function deleteUploads($uploadIds){
        Upload::in('id',$uploadIds)->delete();
    }

    public function uploadFiles($files, $user_id, $relPath = 'uploads'){
        $resp = new AppResponse(true);
        if(empty($relPath)){
            $relPath = 'uploads';
        }

        $mods = [];

        if(!empty($files)){
            foreach ($files as $file) {
                $extension = $file->extension();
                $content = file_get_contents($file->path());
                $originalName = $file->getClientOriginalName();
                $mods[] = $this->storeFile($extension,$originalName,$content,$user_id, $relPath);
            }

            if(count($mods)>0){
                $resp->data = $mods;
            }
        }else{
            $resp->addError('file','Please select files to upload');
        }

        return $resp;
    }

    protected function pngFileContentFromBase64Url($base64Url){
        list($type, $base64Url) = explode(';', $base64Url);
        list(, $base64Url)      = explode(',', $base64Url);
        $base64Url = base64_decode($base64Url);
        return $base64Url;
    }

    public function uploadFilesBase64($files, $user_id, $relPath = 'uploads'){
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
                $mods[] = $this->storeFile($extension,$originalName,$content,$user_id,$relPath);
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