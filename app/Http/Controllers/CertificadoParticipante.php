<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CertificadoParticipante extends Controller
{
    public function print($id) {

        $inscricao = Inscricao::find($id);

        //Nome do Inscrito

        if($inscricao->inscricao_tipo == 1) {
            $nomeInscrito = $inscricao->discente->name;
            
        } 
        elseif($inscricao->inscricao_tipo == 2) {
            $nomeInscrito = $inscricao->user->name;
            
        }
        else {
            $nomeInscrito = $inscricao->nome;
        } 

        $ContProg = $inscricao->acao->ConteudoProgramatico;
       

        return Pdf::loadView('pdf.CertificadoParticipante', compact(['inscricao','nomeInscrito','ContProg']))->setPaper('A4', 'landscape')->stream();
    }
}
