<?php
namespace App\Receipt\Builders;

use App\Models\Employee;
use App\Receipt\ReceiptType;
use App\Repositories\EmployeeRepository;
use App\Receipt\Builders\ReceiptBuilderContract;
use App\Classes\Notification\Receipt\GenericReceipt;

class ReceiptHostBuilder implements ReceiptBuilderContract{

    protected $employee;
    
    /**
     * Undocumented function
     *
     * @param Employee $type
     */
    public function __construct($type = null)
    {
        if($type!==null) {
            $this->employee = $type;
        }
    }

    function buildReceiptContract(){
        $ReceiptContracts = [GenericReceipt::createFromEmployee($this->employee)];
        return $ReceiptContracts;
    }
    
    /**
     * @return string
     */
    function displayText(){
        return 'Visitor\'s Host';
    }

    function jsonSerialize()
    {
        return [
            'identifier'=>base64_encode(json_encode($this->getIdentificationInfo())),
            'identification_info'=>$this->getIdentificationInfo(),
            'displayText'=>$this->displayText()
        ];
    }


    /**
     * @param string|array $data
     */
    function initFromIdentificationInfo($data){
    }

    /**
     * JSON {type: string} & {[index:string]:any}
     */
    function getIdentificationInfo(){
        return [
            'type'=>$this->getType()
        ];
    }

    /**
     * @return integer
     */
    function getRegistrableId(){
        return null;
    }
    
    function setRegistrableId($id){
        
    }


    function getType() {
        return ReceiptType::Host;
    }
}