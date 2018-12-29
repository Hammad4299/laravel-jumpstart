<?php

namespace App\Trigger;

use App\Classes\Helper;
use Illuminate\Support\Collection;
use App\Models\NotificationTemplate;
use App\Trigger\Models\TriggerContract;
use App\Trigger\Factory\TriggerTypeFactory;
use App\Repositories\NotificationTemplateRepository;

class NotificationTriggerManager {
    /**
     * @var TriggerContext
     */
    protected $context;

    /**
     * @var TriggerManager
     */
    protected $manager;

    public function __construct(TriggerContext $context)
    {
        $this->context = $context;
        $this->manager = new TriggerManager($context);
    }

    /**
     * @return Collection|TriggerContract[]
     */
    public function getAvailableTriggers() {
        return $this->manager->getAvailableTriggersForTypes([
            TriggerType::CheckinToSeeHost,
            TriggerType::VisitorTypeCheckin
        ]);
    }

    public function updateNotificationTrigger($notification_id, $identification_info) {
        $repo = new NotificationTemplateRepository();
        $resp = $repo->get($notification_id);
        if($resp->getStatus()) {
            if(!empty($resp->data->trigger_id)) {
                $this->manager->deleteTriggers([$resp->data->trigger_id]);
            }

            $resp = $this->manager->persist($identification_info);
            if($resp->getStatus()) {
                $resp = $repo->setTrigger($notification_id, $resp->data->id);
            }
        }

        return $resp;
    }

    /**
     * Can be called, for instance in Event Listener for visitor checkin
     *
     * @param [type] $visitor
     * @param [type] $host
     * @param [type] $visitorType
     * @return void
     */
    public function triggerNotificationOnCheckin($visitor, $host, $visitorType) {
        $collection = new Collection();
        $collection = $collection->concat($this->manager->getTriggered(TriggerType::VisitorTypeCheckin, $visitorType));
        $collection = $collection->concat($this->manager->getTriggered(TriggerType::CheckinToSeeHost, $host));
        $repo = new NotificationTemplateRepository();
        $resp = $repo->getTriggeredNotifications($collection->pluck('id'));
        if($resp->getStatus()) {
            //TODO send notifications. Eager load or separate load receipts/sending channel data/credentials
        }
        
        return $resp;
    }

    public function fillTriggersForNotifications($notifications) {
        $col = Helper::toCollection($notifications);
        $triggerIds = $col->filter(function($notification) {
            return !empty($notification->trigger_id);
        })->pluck('trigger_id');
        
        $loaded = $this->manager->getTriggerDetailsForIds($triggerIds);
        foreach ($notifications as $notification) {
            /**
             * @var NotificationTemplate $notification
             */
            $notification->trigger_info = Helper::getKeyValue($loaded,$notification->trigger_id);
        }
    }
}