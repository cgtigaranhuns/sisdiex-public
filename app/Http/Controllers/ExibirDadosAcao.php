<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExibirDadosAcao extends Controller
{
    public function show($id)
    {
        $acao = \App\Models\Acao::find($id);
        return view('filament.modal.modal-exibir-dados-acao', compact('acao'));
    }
}
