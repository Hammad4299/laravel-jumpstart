<?php

namespace App\Models;

trait Activable
{
    public function scopeFilterActive($q){
        return $q;
    }

    public function scopeFilterByState($q, $state){
        return $q;
    }

    public function scopeFilterInactive($q){
        return $q;
    }

    public function scopeFilterActivable($q, $stateOrActive = null){
        if($q === null){
        } else if($stateOrActive === true){
            $q = $q->filterActive();
        } else if($stateOrActive === false){
            $q = $q->filterInactive();
        } else if($stateOrActive !== null){
            $q = $q->filterByState($stateOrActive);
        }
        
        return $q;
    }
}