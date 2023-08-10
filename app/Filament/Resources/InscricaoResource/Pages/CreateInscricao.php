<?php

namespace App\Filament\Resources\InscricaoResource\Pages;

use App\Filament\Resources\InscricaoResource;
use App\Models\Inscricao;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateInscricao extends CreateRecord
{
    protected static string $resource = InscricaoResource::class;

    
      
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
