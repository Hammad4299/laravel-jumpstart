<?
namespace App\Receipt\ReceiptManagers;

use App\Receipt\ReceiptContext;
use Illuminate\Support\Collection;
use App\Repositories\EmployeeRepository;
use App\Receipt\Builders\ReceiptEmployeeBuilder;
use App\Models\Employee;

class ReceiptEmployeeBuilderManager implements ReceiptBuilderManagerContract{

    /**
     * @var ReceiptContext
     */
    protected $context;

    /**
     * Undocumented variable
     *
     * @var EmployeeRepository
     */
    protected $repo;

    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new EmployeeRepository();
    }


    /**
     * @param ReceiptEmployeeBuilder[] $receiptBuilders
     */
    function persist($receiptBuilders){}


    /**
     * @param integer[]|Collection $ids
     * @return ReceiptEmployeeBuilder[]|Collection
     */
    function getByRegistrableIds($ids){
        $col = null;
        $resp = $this->repo->getByIds($ids);
        
        if($resp->getStatus()) {
            $col = $resp->data->map(function(Employee $item) {
                return new ReceiptEmployeeBuilder($item);
            });
        }
        
        return $col;
    }
    
    /**
     * @return ReceiptEmployeeBuilder[]
     */
    function getReceiptBuilder(){
        $col = null;
        $resp = $this->repo->index([
            'location_id'=>$this->context->getLocationId()
        ]);
        
        if($resp->getStatus()) {
            $col = $resp->data->map(function(Employee $item) {
                return new ReceiptEmployeeBuilder($item);
            });
        }
        
        return $col; 
    }
}