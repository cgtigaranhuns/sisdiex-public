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

    protected function beforeCreate(): void
        {
                $acao = Acao::find($this->data['acao_id']);
              //  dd($this->data);
                Mail::raw('Sua inscrição para o Evento/Ação: '.$acao->titulo.', está em análise.', function($msg) {
                    $msg->to($this->data['email'])->subject('Inscrição cadastrada'); 
                }); 

                Mail::raw('Inscrição realizada para o Evento/Ação: '.$acao->titulo.'.', function($msg) {
                    $msg->to('wellington.cavalcante@garanhuns.ifpe.edu.br')->subject('Inscrição realizada'); 
                }); 
        }

}
