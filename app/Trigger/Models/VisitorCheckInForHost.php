<?php

namespace App\Trigger\Models;

use App\Classes\Helper;
use App\Models\Employee;
use App\Trigger\TriggerType;

class VisitorCheckinForHost implements TriggerContract {
    protected $name;
    protected $hostId;
    protected $id;

    public function __construct($type = null)
    {
        if($type!==null) {
            $this->name = $type->name;
            $this->setHostId($type->id);
        }
    }

    /**
     * @param string|array $data
     */
    function initFromIdentificationInfo($data)
    {
        $data = Helper::toDecoded($data);
        $this->setHostId(Helper::getKeyValue($data,'hostId'));
    }

     /**
     * JSON string
     * @return string
     */
    function getIdentificationInfo() {
        return json_encode([
            'type'=>$this->getType(),
            'hostId'=>$this->gethostId()
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
        return 'When someone visits %name%';
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
        return TriggerType::CheckinToSeeHost;
    }

    /**
     * Get the value of hostId
     */ 
    public function gethostId()
    {
        return $this->hostId;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of hostId
     *
     * @return  self
     */ 
    public function setHostId($hostId)
    {
        $this->hostId = $hostId;
        $this->id = $hostId;
        return $this;
    }
}