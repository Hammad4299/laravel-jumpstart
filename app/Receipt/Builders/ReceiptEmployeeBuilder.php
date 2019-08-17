<?php
namespace App\Receipt\Builders;

use App\Classes\Helper;
use App\Models\Employee;
use App\Receipt\ReceiptType;
use App\Repositories\EmployeeRepository;
use App\Receipt\Builders\ReceiptBuilderContract;
use App\Classes\Notification\Receipt\GenericReceipt;

class ReceiptEmployeeBuilder implements ReceiptBuilderContract{

    protected $name;
    protected $employeeId;
    protected $employe_repo;
    protected $employee;

    /**
     * Undocumented function
     *
     * @param Employee $employee
     */
    public function __construct($employee = null)
    {
        if($employee!==null) {
            $this->name = $employee->name;
            $this->setRegistrableId($employee->id);
            $this->employee = $employee;
        }

        $this->employe_repo = new EmployeeRepository();
    }

    function buildReceiptContract(){
        $ReceiptContracts = [GenericReceipt::createFromEmployee($this->employee)];
        // dd('employee', $ReceiptContracts);
        return $ReceiptContracts;
    }
    
    /**
     * @return string
     */
    function displayText(){
        return $this->name;
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
            'id'=>$this->employeeId
        ];
    }

    /**
     * @return integer
     */
    function getRegistrableId(){
        return $this->employeeId;
    }
    
    function setRegistrableId($id){
        $this->employeeId = $id;
    }


    function getType() {
        return ReceiptType::Employee;
    }
}