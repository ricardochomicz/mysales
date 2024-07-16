<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserService extends BaseService
{

    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function toSelectUserClient()
    {
        return User::where('tenant_id', auth()->user()->tenant->id)
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 6); //supervisor
            })
            ->orderBy('name')
            ->get(['id', 'name']);
    }


    public function toSelectSupervisor()
    {
        return User::where('tenant_id', auth()->user()->tenant->id)
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 4); //supervisor
            })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function toSelectUsers($filter = false)
    {
        return User::where('tenant_id', auth()->user()->tenant->id)
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 6); //usuário
            })
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')
                    ->from('team_user');
            })
            ->when($filter, function ($query, $filter) {
                $query->where('name', 'like', '%' . $filter . '%');
            })
            ->orderBy('name')
            ->get()->toArray();
    }

    protected function getQuery($filter)
    {
        if (Gate::allows('isSuperAdmin')) {
            $user = User::with('tenant')
                ->whereTenantId(auth()->user()->tenant->id)
                ->filter($filter);
        } elseif (Gate::allows('isAdmin')) {
            $user = User::with('tenant')
                ->whereTenantId(auth()->user()->tenant->id)
                ->where('id', '<>', 1)
                ->filter($filter);
        } else {
            $user = User::with('tenant')
                ->whereTenantId(auth()->user()->tenant->id)
                ->where('id', auth()->user()->id)
                ->filter($filter);
        }

        return $user;
    }


    public function get($id, $withTrashed = false)
    {
        if (Gate::allows('isAdmin')) {
            return $withTrashed ?
                User::with('tenant')->whereTenantId(auth()->user()->tenant->id)->withTrashed()->find($id) :
                User::with('tenant')->whereTenantId(auth()->user()->tenant->id)->find($id);
        } else {
            return $withTrashed ?
                User::with('tenant')->whereTenantId(auth()->user()->tenant->id)->whereId(auth()->user()->id)->withTrashed()->find($id) :
                User::with('tenant')->whereTenantId(auth()->user()->tenant->id)->whereId(auth()->user()->id)->find($id);
        }
    }


    protected function beforeDestroy($model): void
    {
        if ($model->deleted_at != null) {
            $model->restore();
        } else {
            $model->delete();
        }
    }

    protected function handleBeforeSave(array $data): array
    {
        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = bcrypt($this->formatPhone($data['phone']));
        }

        if (isset($data['avatar'])) {
            $data['avatar'] = $this->uploadAvatar($data['avatar']);
        }

        return $data;
    }

    protected function afterSave($model, array $data): void
    {
        if (isset($data['role_id'])) {
            $model->roles()->attach($data['role_id']);
        }
    }

    protected function handleUpdate(array $data, $model): array
    {
        if (isset($data['avatar'])) {
            if ($model->avatar) {
                Storage::disk('public')->delete($model->avatar);
            }
            $data['avatar'] = $this->uploadAvatar($data['avatar']);
        }

        return $data;
    }

    protected function afterUpdate($model, array $data): void
    {
        if (isset($data['role_id'])) {
            $model->roles()->sync($data['role_id']);
        }
    }


    private function formatPhone($value): array|string
    {
        return str_replace(' ', '', str_replace('-', '', $value));
    }

    private function uploadAvatar($data)
    {
        return $data->store('/tenant/' . auth()->user()->tenant->id . '/user/' . auth()->user()->id, "public");
    }

}
