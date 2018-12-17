<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 7/20/2017
 * Time: 7:56 PM
 */

namespace App\Pipelines;
use Closure;

abstract class BasePipeline
{
    protected $pipeline;

    public function __construct()
    {
        $this->pipeline = null;
    }

    protected function constructPipeline(){
        $this->pipeline = app('Illuminate\Pipeline\Pipeline');
        $this->pipeline->through(
            array_merge([],$this->pipelineStages())
        );
    }

    protected abstract function pipelineStages();

    protected abstract function setPipelineData();

    public function execute(Closure $finalCallback){
        $this->constructPipeline();
        $this->setPipelineData();
        $this->pipeline->then($finalCallback);
    }
}