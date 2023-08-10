<?php

namespace App\Filament\Resources\InscricaoResource\Pages;

use App\Filament\Resources\InscricaoResource;
use Closure;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListInscricaos extends ListRecords
{
    protected static string $resource = InscricaoResource::class;

    protected static ?string $title = 'Suas Inscrições';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova Inscrição'),
        ];
    }

        
}
