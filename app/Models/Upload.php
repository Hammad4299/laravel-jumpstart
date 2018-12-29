<?php

namespace App\Models;

use App\Traits\ModelTrait;
use App\Storage\FileHandle;
use App\Storage\CrossFileHandle;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    public $table = 'uploads';
    use ModelTrait;
    protected $decodedMeta;
    const META_ORIGINAL_NAME = 'orig_name';
    const META_STORED_NAME = 'stored_name';
    const META_STORED_EXT = 'stored_ext';
    const META_STORED_NAME_WITHOUT_EXT = 'stored_name_without_ext';

    /**
     * @var CrossFileHandle
     */
    protected $fileHandleProvider;
    protected $pdffileHandleProvider;

    public function getFileHandleProvider() {
        if(!$this->fileHandleProvider) {
            $this->fileHandleProvider = new CrossFileHandle(FileHandle::PROVIDER_DEFAULT, $this->rel_path, false);
        }

        return $this->fileHandleProvider;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rel_path',
        'metadata',
        'uploaded_at',
        'owner_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'metadata'
    ];

    protected $appends = [
        'full_url',
        'original_name'
    ];

    protected function updateMeta($attrib, $val){
        $meta = $this->metadata_decoded;
        if($meta == null){
            $meta = [];
        }
        $meta[$attrib] = $val;
        $this->decodedMeta = $meta;
        $this->metadata = json_encode($meta);
    }

    public function setOriginalNameAttribute($value){
        $this->updateMeta(Upload::META_ORIGINAL_NAME,$value);
    }

    public function setPdfRelPathAttribute($value){
        $this->updateMeta(Upload::META_PDF_REL_PATH,$value);
    }

    public function getMetadataDecodedAttribute(){
        if(empty($this->decodedMeta)){
            $this->decodedMeta = isset($this->metadata) ? json_decode($this->metadata,true) : null;
        }

        return $this->decodedMeta;
    }

    public function getOriginalNameAttribute(){
        return $this->getMetadataDecodedAttribute()[self::META_ORIGINAL_NAME];
    }

    public function getFullUrlAttribute(){
        return (new FileHandle(FileHandle::PROVIDER_PUBLIC,$this->rel_path))->getFullUrl();
    }

    public $timestamps = false;
}
