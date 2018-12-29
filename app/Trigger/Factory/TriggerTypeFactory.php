<?php

namespace App\Trigger\Factory;

use App\Trigger\TriggerType;
use App\Trigger\TriggerContext;
use App\Trigger\Models\TriggerContract;
use App\Trigger\TriggerType\CheckInForHost;
use App\Trigger\TriggerType\VisitorCheckIn;
use App\Trigger\Models\VisitorCheckinForHost;
use App\Trigger\TriggerType\TriggerTypeContract;
use App\Trigger\Models\VisitorTypeCheckIn;

class TriggerTypeFactory {
    /**
     * @param string $type
     * @param TriggerContext $context
     * @return TriggerTypeContract
     */
    public function getForType($type, TriggerContext $context) {
        $toRet = null;
        if($type === TriggerType::VisitorTypeCheckin) {
            $toRet = new VisitorCheckIn($context);
        } else if ($type === TriggerType::CheckinToSeeHost) {
            $toRet = new CheckInForHost($context);
        }
        return $toRet;
    }

    /**
     * @param string $type
     * @param array $identification_info
     * @return TriggerContract
     */
    public function getTrigger($type, $identification_info) {
        $toRet = null;
        if($type === TriggerType::VisitorTypeCheckin) {
            $toRet = new VisitorTypeCheckIn();
        } else if ($type === TriggerType::CheckinToSeeHost) {
            $toRet = new VisitorCheckinForHost();
        }
        
        if($toRet!==null) {
            $toRet->initFromIdentificationInfo($identification_info);
        }
        return $toRet;
    }
}