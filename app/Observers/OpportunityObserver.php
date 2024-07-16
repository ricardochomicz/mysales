<?php

namespace App\Observers;

use App\Models\Opportunity;
use Illuminate\Support\Str;

class OpportunityObserver
{
    public function creating(Opportunity $opportunity)
    {
        $opportunity->uuid = Str::uuid();
    }
}
