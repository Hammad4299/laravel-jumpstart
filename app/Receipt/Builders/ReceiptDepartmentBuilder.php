<?php

namespace App\Receipt\Builders;

use App\Classes\Helper;
use App\Models\Department;
use App\Receipt\ReceiptType;
use App\Repositories\EmployeeRepository;
use App\Receipt\Builders\ReceiptBuilderContract;
use App\Classes\Notification\Receipt\GenericReceipt;

class ReceiptDepartmentBuilder implements ReceiptBuilderContract{

    protected $name;
    protected $departmentId;
    protected $employee_repo;

    /**
     * 
     *
     * @param Department $department
     */
    public function __construct($department = null)
    {
        if($department!==null) {
            $this->name = $department->name;
            $this->setRegistrableId($department->id);
        }

        $this->employee_repo = new EmployeeRepository();
    }

    function buildReceiptContract(){
        $employees = $this->employee_repo->getDepartmentEmployee($this->departmentId)->data;
        $ReceiptContracts = [];
        foreach ($employees as $employee) {
            array_push($ReceiptContracts, GenericReceipt::createFromEmployee($employee));
        }

        return $ReceiptContracts;
    }
    
    /**
     * @return string
     */
    function displayText(){
        return $this->name.' Department';
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
        $data = Helper::toDecoded($data);
        $this->setRegistrableId(Helper::getKeyValue($data,'id'));
    }


    /**
     * JSON {type: string} & {[index:string]:any}
     */
    function getIdentificationInfo(){
        return [
            'type'=>$this->getType(),
            'id'=>$this->departmentId
        ];
    }

    /**
     * @return integer
     */
    function getRegistrableId(){
        return $this->departmentId;
    }
    
    function setRegistrableId($id){
        $this->departmentId = $id;
    }


    function getType() {
        return ReceiptType::Department;
    }
}