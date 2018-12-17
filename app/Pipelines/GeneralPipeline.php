<?php
/**
 * Created by PhpStorm.
 * User: talha
 * Date: 7/20/2017
 * Time: 7:56 PM
 */

namespace App\Pipelines;

class GeneralPipeline extends BasePipeline
{
    protected $pipeline;
    public $pipelineStages;
    public $pipelineData;


    protected function pipelineStages(){
        return $this->pipelineStages;
    }

    protected function setPipelineData() {
        $this->pipeline->send($this->pipelineData);
    }
}