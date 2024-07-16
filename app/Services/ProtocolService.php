<?php

namespace App\Services;

use App\Models\Protocol;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProtocolService
{

    public function index($data)
    {
        $protocols = Protocol::with('client', 'operadora');
        if (in_array([1, 2, 3], auth()->user()->roles->pluck('id')->toArray())) {
            $protocols->where('tenant_id', auth()->user()->tenant->id)

                ->orderBy('updated_at', 'asc')
                ->filter($data);
        }else{
            $protocols->where('tenant_id', auth()->user()->tenant->id)
                ->where('user_id', auth()->user()->id)

                ->orderBy('updated_at', 'asc')
                ->filter($data);
        }
        return $protocols->paginate();
    }


    public function store($data)
    {
        DB::beginTransaction();
        try {
            $data['tenant_id'] = auth()->user()->tenant->id;
            $data['user_id'] = auth()->user()->id;

            $data['prompt'] = $this->diasUteis(Carbon::now()->format("Y-m-d"), 5);
            $protocol = new Protocol($data);
            $protocol->save();

            $user = User::where('id', $protocol->user_id)->first();


//            if(isset($data['enviar_email']) && $data['enviar_email']){
//                Mail::to($user->email)
//                    ->cc('comercial@42telecom.com.br')
//                    ->send(new NewProtocol($protocol));
//            }


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function get($id)
    {
        return Protocol::where('tenant_id', auth()->user()->tenant->id)->find($id);
    }

    public function update($data,$id)
    {
        DB::beginTransaction();
        try {
            $protocol = $this->get($id);

            if (isset($data['archive']) && $data['archive']) {
                if ($protocol->archive) {
                    Storage::disk('public')->delete($protocol->id);
                }
                $data['archive'] = $this->gravaArquivo($protocol['id'], $data['archive']);
            }

            if ($data['status'] == 3) {
                $data['prompt'] = $data['closure'];
            }

            $user = User::where('id', $protocol->user_id)->first();

            $protocol->update($data);

//            if($data['status_id'] == 3){
//                Mail::to($user->email)
//                    ->cc('comercial@42telecom.com.br')
//                    ->send(new NewProtocol($protocol));
//            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    private function gravaArquivo($id, $data)
    {
        return $data->store('/docs/' . $id, "public");
    }

    private function whatsapp($phone, $texto)
    {
        $link = 'https://wa.me/55"{$phone}"?text="{$texto}"';
        return $link;
    }


    private function diasUteis($str_data, $int_qtd_dias_somar = 5)
    {
        $str_data = substr($str_data, 0, 10);
        if (preg_match("@/@", $str_data) == 1) {
            $str_data = implode("-", array_reverse(explode("/", $str_data)));
        }
        $array_data = explode('-', $str_data);
        $count_days = 0;
        $int_qtd_dias_uteis = 0;
        while ($int_qtd_dias_uteis < $int_qtd_dias_somar) {
            $count_days++;
            if (($dias_da_semana = gmdate('w', strtotime('+' . $count_days . ' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0])))) != '0' && $dias_da_semana != '6') {
                $int_qtd_dias_uteis++;
            }
        }
        return gmdate('Y-m-d', strtotime('+' . $count_days . ' day', strtotime($str_data)));
    }
}
