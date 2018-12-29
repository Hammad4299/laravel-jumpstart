<?php

namespace App\Trigger\Models;

interface TriggerContract extends \JsonSerializable {
    /**
     * @param string|array $data
     */
    function initFromIdentificationInfo($data);

    /**
     * JSON string
     * @return string
     */
    function getIdentificationInfo();

    /**
     * Identifier that can be used to register trigger elsewhere in system. 
     * e.g. you can store this id in separate table contain all trigger types but still 
     * keeping responsibiliy for persisting data for specific trigger type to their respective type
     * @return integer
     */
    function getRegisterableId();

    function setRegistrableId($id);

    /**
     * A string containing placeholders that will be replaced by getDescriptionParams() to allow bolding/styling description in UI
     * As separate JSON key
     * @return string
     */
    function getDescriptionString();

    /**
     * A replacement params for getDescriptionString();
     * As separate JSON key
     * @return string
     */
    function getDescriptionParams();

    /**
     * @return string
     */
    function getType();
}