<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(protected WalletService $walletService)
    {
    }

    public function index()
    {
        $view = [];
        return view('pages.wallets.index', $view);
    }

    public function cloneWallet($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->walletService->cloneWallet($id);
            notyf()->success('Oportunidade criada com sucesso.');
            return redirect()->route('opportunities.index');
        } catch (\Throwable $e) {
            notyf()->error("Ops! Erro ao cadastrar.");
            return back();
        }


    }
}
