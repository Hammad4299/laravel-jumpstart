<?php

namespace App\Trigger\Models;

use App\Classes\Helper;
use App\Models\VisitorType;
use App\Trigger\TriggerType;

class VisitorTypeCheckIn implements TriggerContract {
    protected $name;
    protected $visitorTypeId;
    protected $id;

    public function __construct($type = null)
    {
        if($type!==null) {
            $this->name = $type->name;
            $this->setVisitorTypeId($type->id);
        }
    }
    
    /**
     * @param string|array $data
     */
    function initFromIdentificationInfo($data)
    {
        $data = Helper::toDecoded($data);
        $this->setVisitorTypeId(Helper::getKeyValue($data,'visitorTypeId'));
    }

     /**
     * JSON string
     * @return string
     */
    function getIdentificationInfo() {
        return json_encode([
            'type'=>$this->getType(),
            'visitorTypeId'=>$this->visitorTypeId
        ]);
    }

    function jsonSerialize()
    {
        return [
            'identification_info'=>$this->getIdentificationInfo(),
            'description'=>$this->getDescriptionString(),
            'descriptionParams'=>$this->getDescriptionParams()
        ];
    }

    /**
     * Identifier that can be used to register trigger elsewhere in system. 
     * e.g. you can store this id in separate table contain all trigger types but still 
     * keeping responsibiliy for persisting data for specific trigger type to their respective type
     * @return integer
     */
    function getRegisterableId() {
        return $this->id;
    }

    function setRegistrableId($id) {
        $this->id = $id;
    }

    /**
     * A string containing placeholders that will be replaced by getDescriptionParams() to allow bolding/styling description in UI
     * As separate JSON key
     * @return string
     */
    function getDescriptionString() {
        return 'When a %name% visitor checks in';
    }

    /**
     * A replacement params for getDescriptionString();
     * As separate JSON key
     * @return string
     */
    function getDescriptionParams() {
        return [
            'name'=>$this->name
        ];
    }

    /**
     * @return string
     */
    function getType() {
        return TriggerType::VisitorTypeCheckin;
    }

    /**
     * Get the value of visitorTypeId
     */ 
    public function getVisitorTypeId()
    {
        return $this->visitorTypeId;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of visitorTypeId
     *
     * @return  self
     */ 
    public function setVisitorTypeId($visitorTypeId)
    {
        $this->visitorTypeId = $visitorTypeId;
        $this->id = $visitorTypeId;
        return $this;
    }
}