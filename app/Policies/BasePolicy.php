<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;

class BasePolicy
{

    public function before($user, $ability)
    {
        // if ($this->getAccessInfo($user->id)->isSuperAdmin()) {
            // return true;
        // }
    }
}