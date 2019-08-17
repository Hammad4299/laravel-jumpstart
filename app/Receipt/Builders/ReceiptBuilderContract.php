<?php

namespace App\Receipt\Builders;

use App\Classes\Notification\Receipt\ReceiptContract;

interface ReceiptBuilderContract extends \JsonSerializable {
    /**
     * Undocumented function
     *
     * @return ReceiptContract[]
     */
    function buildReceiptContract();
    
    /**
     * @return string
     */
    function displayText();


    /**
     * @param string|array $data
     */
    function initFromIdentificationInfo($data);


    /**
     * JSON {type: string} & {[index:string]:any}
     */
    function getIdentificationInfo();

    /**
     * @return integer
     */
    function getRegistrableId();
    
    function setRegistrableId($id);
}