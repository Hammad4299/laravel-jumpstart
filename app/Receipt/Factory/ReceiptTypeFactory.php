<?php

namespace App\Receipt\Factory;

use App\Receipt\ReceiptType;
use App\Receipt\ReceiptContext;
use App\Receipt\Builders\ReceiptBuilderContract;
use App\Receipt\Builders\ReceiptDepartmentBuilder;
use App\Receipt\ReceiptManagers\ReceiptBuilderManagerContract;
use App\Receipt\ReceiptManagers\ReceiptDepartmentBuilderManager;
use App\Receipt\ReceiptManagers\ReceiptEmployeeBuilderManager;
use App\Receipt\ReceiptManagers\ReceiptHostBuilderManager;
use App\Receipt\ReceiptManagers\ReceiptVisitorBuilderManager;
use App\Receipt\Builders\ReceiptEmployeeBuilder;
use App\Receipt\Builders\ReceiptHostBuilder;
use App\Receipt\Builders\ReceiptVisitorBuilder;

class ReceiptTypeFactory {
    /**
     * @param string $type
     * @param ReceiptContext $context
     * @return ReceiptBuilderManagerContract
     */
    public function getReceiptBuilderManagerFor($type, ReceiptContext $context) {
        $toRet = null;
        if($type === ReceiptType::Department) {
            $toRet = new ReceiptDepartmentBuilderManager($context);
        } else if($type === ReceiptType::Employee){
            $toRet = new ReceiptEmployeeBuilderManager($context);
        } else if($type === ReceiptType::Host){
            $toRet = new ReceiptHostBuilderManager($context);
        } else if($type === ReceiptType::Visitor){
            $toRet = new ReceiptVisitorBuilderManager($context);
        }
        return $toRet;
    }

    /**
     * @param string $type
     * @param array $identification_info
     * @return ReceiptBuilderContract
     */
    public function getReceiptBuildersFor($type, $identification_info) {
        $toRet = null;
        if($type === ReceiptType::Department) {
            $toRet = new ReceiptDepartmentBuilder();
        } else if($type === ReceiptType::Employee){
            $toRet = new ReceiptEmployeeBuilder();
        } else if($type === ReceiptType::Host){
            $toRet = new ReceiptHostBuilder();
        } else if($type === ReceiptType::Visitor){
            $toRet = new ReceiptVisitorBuilder();
        }
        
        if($toRet!==null) {
            $toRet->initFromIdentificationInfo($identification_info);
        }
        return $toRet;
    }
}