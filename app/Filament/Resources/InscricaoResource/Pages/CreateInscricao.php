<?php

namespace App\Filament\Resources\InscricaoResource\Pages;

use App\Filament\Resources\InscricaoResource;
use App\Mail\NovaInscricao;
use App\Models\Inscricao;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateInscricao extends CreateRecord
{
    protected static string $resource = InscricaoResource::class;

    protected static bool $canCreateAnother = false;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
               
           

            
        ];
    }
      
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
