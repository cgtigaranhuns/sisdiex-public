<?php

namespace App\Filament\Widgets;

use App\Models\Acao as ModelsAcao;
use Carbon\Carbon;
use Closure;
use App\Filament\Resources\InscricaoResource;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class Acao extends BaseWidget
{

    
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string|null
    {
        return 'Inscrições abertas';
    }

    
    

    public function table(Table $table): Table
    {
        
        return $table
            ->query(
              
               \App\Models\Acao::where('status', '=', '2')
                    ->whereDate('data_inicio_inscricoes', '<=', Carbon::today())
                    ->whereDate('data_fim_inscricoes', '>=', Carbon::today())
                    
               
            )
            ->columns([
                TextColumn::make('titulo'),
                TextColumn::make('local'),
                TextColumn::make('data_inicio')
                    ->label('Data Início')
                    ->date('d/m/y'),
                TextColumn::make('data_encerramento')
                    ->label('Data Encerramento')
                    ->date('d/m/y'),
                TextColumn::make('hora_inicio')
                    ->label('Hora Início')
                    ->date('H:i'),
                TextColumn::make('hora_encerramento')
                    ->label('Hora Encerramento')
                    ->date('H:i'), 
                TextColumn::make('tipo_doacao')
                    ->label('Doação'),
                                      
                
               ])->recordUrl( route('filament.admin.resources.inscricaos.create'));
            
            
    }
}
