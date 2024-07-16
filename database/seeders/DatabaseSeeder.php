<?php

namespace Database\Seeders;

use App\Models\OrderType;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Tag;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $plans = ([
            [
                'name' => 'Free',
                'price' => 0.00,
            ],
            [
                'name' => 'Business',
                'price' => 59.90,
            ],
            [
                'name' => 'Premium',
                'price' => 199.90,
            ],
        ]);

        foreach ($plans as $plan) {
            Plan::create($plan);
        }

        //Create Roles
        $roles = [
            [
                'name' => 'SuperAdmin',
                'label' => 'SuperAdmin',
                'description' => 'Administrador Total Sistema'
            ],
            [
                'name' => 'Admin',
                'label' => 'Admin',
                'description' => 'Administrador do Sistema'
            ],
            [
                'name' => 'Manager',
                'label' => 'Gerente',
                'description' => 'Gerente'
            ],
            [
                'name' => 'Supervisor',
                'label' => 'Supervisor',
                'description' => 'Supervisor Equipe'
            ],
            [
                'name' => 'Administrative',
                'label' => 'Administrativo',
                'description' => 'Administrativo'
            ],
            [
                'name' => 'User',
                'label' => 'Usuário',
                'description' => 'Usuário do Sistema'
            ],
        ];

        foreach ($roles as $role) {
            $role = Role::create($role);
        }

        $tenant = Tenant::create([
            'plan_id' => 3, //Premium
            'document' => '27467352000108',
            'name' => 'RICARDO ALEXANDRE CHOMICZ ME',
            'email' => 'ricardo.chomicz@gmail.com',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Ricardo Alexandre Chomicz',
            'email' => 'ricardo.chomicz@gmail.com',
            'phone' => '42988080544',
            'password' => bcrypt('020904'),
        ]);

        $user->roles()->attach(1); //SuperAdmin

        $orderTypes = [
            [
                'tenant_id' => $tenant->id,
                'name' => 'NOVO'
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'PORTABILIDADE'
            ],
            [
                'tenant_id' => $tenant->id,
                'name' => 'RENOVAÇÃO'
            ],
        ];

        foreach ($orderTypes as $order) {
            OrderType::create($order);
        }

        $tag_orders = array(
            [
                'name' => 'ENVIADO BKO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'ANÁLISE DE CRÉDITO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'FATURADO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'ATIVO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'CANCELADO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'REJEITADO CRÉDITO',
                'type' =>  'order',
                'edit' => false
            ],
            [
                'name' => 'PARA CORREÇÃO',
                'type' =>  'order',
                'edit' => false
            ],
        );

        foreach ($tag_orders as $value) {
            Tag::create($value);
        }
    }
}
