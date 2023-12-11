<?php

namespace App\Filament\Resources\InscricaoResource\Pages;

use App\Filament\Resources\InscricaoResource;
use App\Mail\NovaInscricao;
use App\Models\Acao;
use App\Models\Inscricao;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CreateInscricao extends CreateRecord
{
    protected static string $resource = InscricaoResource::class;

    protected static bool $canCreateAnother = false;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),


        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        //TIPO DE INSCRITO
        if ($this->record->inscricao_tipo == 1) {
            $nomeInscrito = $this->record->discente->name;
        }
        if ($this->record->inscricao_tipo == 2) {
            $nomeInscrito = $this->record->user->name;
        }
        if ($this->record->inscricao_tipo == 3) {
            $nomeInscrito = $this->record->nome;
        }

        $acao = Acao::find($this->data['acao_id']);

        // DOAÇÃO
        if ($this->record->acao->doacao == 1) {
            Mail::raw('Olá, ' . $nomeInscrito . ', sua inscrição para o Evento/Ação: ' . $acao->titulo . ', está em análise. ATENÇÃO: Para confirmar sua inscrição, voçê deverá entregar a doação de '.$acao->tipo_doacao.', na DIEX.', function ($msg) {
                $msg->to($this->data['email'])->subject('Inscrição cadastrada');
            });
        } else {
            Mail::raw('Olá ' . $nomeInscrito . ', sua inscrição para o Evento/Ação: ' . $acao->titulo . ', está em análise.', function ($msg) {
                $msg->to($this->data['email'])->subject('Inscrição cadastrada');
            });
        }

        //EMAIL DE RETORNO PARA DIEX

        Mail::raw('Inscrição realizada para o Evento/Ação: ' . $acao->titulo . '.', function ($msg) {
            $msg->to('wellington.cavalcante@garanhuns.ifpe.edu.br')->subject('Inscrição realizada');
        });
    }
}
