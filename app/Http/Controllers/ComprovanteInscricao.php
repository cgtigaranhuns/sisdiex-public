<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComprovanteInscricao extends Controller
{
    public function print($id)
    {
        
            
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

        //Turno da ação

            if($inscricao->acao->turno == 1) {
                $turnoNome = 'Manhã';
            }
            elseif($inscricao->acao->turno == 2) {
                $turnoNome = 'Tarde';
            }
            else {
                $turnoNome = 'Noite';
            }

        //Status da Ação
        
        if($inscricao->acao->status == 1) {
            $nomeStatus = 'Em Análise';
        }
        elseif($inscricao->acao->status == 2) {
            $nomeStatus = 'Aprovada';
        }
        else {
            $nomeStatus = 'Reprovada';
        }
                
            
             

            return Pdf::loadView('pdf.Comprovante', compact(['inscricao','nomeInscrito', 'turnoNome','nomeStatus']))->stream();
             // return view('pdf.contrato', compact(['locacao']));
       
    }
}
