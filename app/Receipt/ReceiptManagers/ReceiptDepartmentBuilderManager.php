<?
namespace App\Receipt\ReceiptManagers;

use App\Receipt\ReceiptContext;
use Illuminate\Support\Collection;
use App\Receipt\Builders\ReceiptDepartmentBuilder;
use App\Models\Department;
use App\Repositories\DepartmentRepository;

class ReceiptDepartmentBuilderManager implements ReceiptBuilderManagerContract{

    /**
     * @var ReceiptContext
     */
    protected $context;
    protected $repo;

    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new DepartmentRepository();
    }


    /**
     * @param ReceiptDepartmentBuilder[] $receiptBuilders
     */
    function persist($receiptBuilders){}


    /**
     * @param integer[]|Collection $ids
     * @return ReceiptDepartmentBuilder[]|Collection
     */
    function getByRegistrableIds($ids){
        $col = null;
        $resp = $this->repo->getByIds($ids);
        
        if($resp->getStatus()) {
            $col = $resp->data->map(function(Department $item) {
                return new ReceiptDepartmentBuilder($item);
            });
        }
        
        return $col; 
    }
    
    /**
     * @return ReceiptDepartmentBuilder[]
     */
    function getReceiptBuilder(){
        $col = null;
        $resp = $this->repo->index([
            'location_id'=>$this->context->getLocationId()
        ]);
        
        if($resp->getStatus()) {
            $col = $resp->data->map(function(Department $item) {
                return new ReceiptDepartmentBuilder($item);
            });
        }
        
        return $col; 
    }
}