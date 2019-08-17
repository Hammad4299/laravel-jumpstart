<?
namespace App\Receipt\ReceiptManagers;

use App\Receipt\ReceiptContext;
use Illuminate\Support\Collection;
use App\Repositories\EmployeeRepository;
use App\Receipt\Builders\ReceiptHostBuilder;
use App\Models\Employee;

class ReceiptHostBuilderManager implements ReceiptBuilderManagerContract{

    /**
     * @var ReceiptContext
     */
    protected $context;
    protected $repo;

    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new EmployeeRepository();
    }


    /**
     * @param ReceiptHostBuilder[] $receiptBuilders
     */
    function persist($receiptBuilders){}


    /**
     * @param integer[]|Collection $ids
     * @return ReceiptHostBuilder[]|Collection
     */
    function getByRegistrableIds($ids){
        $col = new Collection();
        $col->push(new  ReceiptHostBuilder($this->context->getHost()));
        return $col;
    }
    
    /**
     * @return ReceiptHostBuilder[]
     */
    function getReceiptBuilder(){
        $col = new Collection();
        $col->push(new  ReceiptHostBuilder());
        return $col;
    }
}