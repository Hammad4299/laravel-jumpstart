<?php

namespace App\DTO;

use App\Classes\Helper;
use App\Storage\FileHandleFactoryContract;
use App\Storage\FileHandleFactory;

/**
 * @property string $full_url
 * @property string $upload_rel
 */
class FileDTO extends JSONMeta {
    /**
     * @var CrossFileHandle
     */
    protected $fileHandle;

    /**
     * @var FileHandleFactoryContract
     */
    protected $contract;

    public function setContract(FileHandleFactoryContract $contract) {
        $this->contract = $contract;
    }

    function defaults($json)
    {
        return array_merge(parent::defaults($json),[
            'upload_rel'=>null
        ]);
    }

    public function initFromJson($json) {
        parent::initFromJson($json);
        $this->setting = [
            'upload_rel'=>Helper::getKeyValue($this->setting,'upload_rel',null)
        ];  //remove any extra props like full_url
    }

    /**
     * @return CrossFileHandle
     */
    public function getFileHandle() {
        if($this->fileHandle===null) {
            $this->fileHandle = $this->contract->forExisting($this->upload_rel);
        }

        return $this->fileHandle;
    }

    public function &__get($name)
    {
        $upload_rel = parent::__get('upload_rel');
        if($name === 'full_url' && !empty($upload_rel)) {
            $r = $this->getFileHandle()->getFullUrl();
            return $r;
        }
        return parent::__get($name);
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(),[
            'upload_rel'=>$this->upload_rel,
            'full_url'=>$this->full_url
        ]);
    }
}