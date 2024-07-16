<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'document' => ['required', 'string', 'max:255', 'unique:tenants'],
            'tenant' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'document.required' => 'Informe o CNPJ da Empresa',
            'document.unique' => 'CNPJ informado já possui cadastro.',
            'tenant.required' => 'Informe o nome da empresa.',
            'email.required' => 'Informe o e-mail',
            'email.unique' => 'E-mail informado já cadastrado',
            'password.required' => 'Informe sua senha'
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     */
    protected function create(array $data)
    {
        if (!$plan = session('plan')) {
            return redirect()->route('site');
        }
        $tenant = $plan->tenants()->create([
            'document' => $data['document'],
            'name' => $data['tenant'],
            'email' => $data['email'],
        ]);

        $user = $tenant->users()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach(2); //Admin

        return $user;
    }
}
