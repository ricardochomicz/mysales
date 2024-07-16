<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Str;

class ClientObserver
{
    public function creating(Client $client): void
    {
        $client->uuid = Str::uuid();
    }
}
