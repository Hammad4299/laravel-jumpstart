<?php
namespace App\Receipt\Builders;

use App\Models\Visitor;
use App\Receipt\ReceiptType;
use function GuzzleHttp\json_encode;
use App\Repositories\VisitorRepository;

use App\Receipt\Builders\ReceiptBuilderContract;
use App\Classes\Notification\Receipt\GenericReceipt;

class ReceiptVisitorBuilder implements ReceiptBuilderContract{
    /**
     * @var Visitor
     */
    protected $visitor;

    public function __construct(Visitor $visitor = null)
    {
        if($visitor!==null) {
            $this->visitor = $visitor;
        }
    }

    function buildReceiptContract(){
        $ReceiptContracts = [GenericReceipt::createFromVisitor($this->visitor)];
        return $ReceiptContracts;
    }
    
    /**
     * @return string
     */
    function displayText(){
        return 'Visitor';
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
    }
    
    function setRegistrableId($id){;
    }


    function getType() {
        return ReceiptType::Visitor;
    }
}