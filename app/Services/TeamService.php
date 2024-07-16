<?php

namespace App\Services;

use App\Models\Team;

class TeamService extends BaseService
{

    public function __construct()
    {
        parent::__construct(Team::class);
    }


    protected function beforeDestroy($model): void
    {
        if ($model->deleted_at != null) {
            $model->restore();
        }else{
            $model->delete();
        }
    }

}
