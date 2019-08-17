<?php

namespace App\Receipt;

use App\Classes\Helper;
use App\Classes\AppResponse;
use App\Receipt\ReceiptContext;
use App\Receipt\ReceiptManager;
use Illuminate\Support\Collection;
use App\Models\NotificationTemplate;
use App\Repositories\ReceiptRepository;
use App\Receipt\Factory\ReceiptTypeFactory;
use App\Receipt\Builders\ReceiptBuilderContract;
use App\Repositories\NotificationTemplateReceiptRepository;

class NotificationReceiptManager {
    /**
     * @var ReceiptContext
     */
    protected $context;

    /**
     * @var ReceiptManager
     */
    protected $manager;

    /**
     * @var NotificationTemplateReceiptRepository
     */
    protected $notificationTemplateReceiptRepo;


    /**
     * @var ReceiptRepository
     */
    protected $receiptRepo;

    /**
     * @var ReceiptTypeFactory
     */
    protected $factory;

    public function __construct(ReceiptContext $context)
    {
        $this->context = $context;
        $this->manager = new ReceiptManager($context);
        $this->factory = new ReceiptTypeFactory();
        $this->notificationTemplateReceiptRepo = new NotificationTemplateReceiptRepository();
        $this->receiptRepo = new ReceiptRepository();
    }

    /**
     * @return Collection|ReceiptBuilderContract[]
     */
    public function getReceiptBuilderFor() {
        $resp = new AppResponse(true);
        $resp->data = $this->manager->getReceiptBuilderFor([
            ReceiptType::Department,
            ReceiptType::Employee,
            ReceiptType::Host,
            ReceiptType::Visitor
        ]);
        return $resp;
    }

    public function updateReceipts($notification_id, $identification_infos){
        $receipts =  [];
        foreach ($identification_infos as $info) {
            array_push($receipts, $this->manager->persist($info)->data);
        }
        
            $receipts = Helper::toCollection($receipts);

            $receiptsId = $receipts->filter(function($receipt) {
                return isset($receipt->id);
            })->pluck('id');

            $this->notificationTemplateReceiptRepo->onNotificationTemplateDeleting([$notification_id]);

            foreach ($receiptsId as $receipt_id) {
                $this->notificationTemplateReceiptRepo->create([
                    'notification_template_id'=>$notification_id,
                    'receipt_id'=>$receipt_id
                ]);
            }
        
    }

    /**
     * @param Collection|NotificationTemplate[] $notifications
     * @return void
     */
    public function fillReceiptBuilders($notifications){
        $receiptIds = [];
        $colNotifications = Helper::toCollection($notifications);
        $receiptIds = $colNotifications->flatMap(function(NotificationTemplate $notification) {
            return $notification->receipts_id;
        })->filter(function($id){
            return $id!==null;
        });
        
        $builders = $this->manager->getReceiptBuilderForIds($receiptIds);
        
        foreach ($notifications as $notification) {
            $col = new Collection();
            foreach ($notification->receipts_id as $receipt_id) {
                if(isset($builders[$receipt_id])){
                    $col->push($builders[$receipt_id]);
                }
            }
            $notification->receipt_builders = $col;
        }
    }
}