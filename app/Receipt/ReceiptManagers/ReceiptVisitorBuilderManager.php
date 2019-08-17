<?
namespace App\Receipt\ReceiptManagers;

use App\Receipt\ReceiptContext;
use Illuminate\Support\Collection;
use App\Repositories\DepartmentRepository;
use App\Receipt\Builders\ReceiptVisitorBuilder;
use App\Models\Visitor;
use App\Repositories\VisitorRepository;

class ReceiptVisitorBuilderManager implements ReceiptBuilderManagerContract{

    /**
     * @var ReceiptContext
     */
    protected $context;

    /**
     * Undocumented variable
     *
     * @var VisitorRepository
     */
    protected $repo;

    public function __construct($context)
    {
        $this->context = $context;
        $this->repo = new VisitorRepository();
    }


    /**
     * @param ReceiptVisitorBuilder[] $receiptBuilders
     */
    function persist($receiptBuilders){}


    /**
     * @param integer[]|Collection $ids
     * @return ReceiptVisitorBuilder[]|Collection
     */
    function getByRegistrableIds($ids){
        $col = new Collection();
        // dd($this->context->getVisitor());
        $col->push(new ReceiptVisitorBuilder($this->context->getVisitor()));
        return $col;
    }
    
    /**
     * @return ReceiptVisitorBuilder[]
     */
    function getReceiptBuilder(){
        $col = new Collection();
        $col->push(new ReceiptVisitorBuilder());
        return $col;
    }
}