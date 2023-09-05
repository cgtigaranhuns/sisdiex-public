<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscricaoResource\Pages;
use App\Filament\Resources\InscricaoResource\RelationManagers;
use App\Models\Acao;
use App\Models\Discente;
use App\Models\Inscricao;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
//use Filament\Pages\Actions\Action;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\DB;
use App\Mail\NovaInscricao;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\CreateAction;



class InscricaoResource extends Resource
{

    public static function getEloquentQuery(): Builder
    {
        // dd(auth()->user()->email);
        return parent::getEloquentQuery()->where('user_criador', '=', auth()->user()->id);
    }


    protected static ?string $model = Inscricao::class;

    protected static ?string $navigationIcon = 'heroicon-s-swatch';

    protected static ?string $navigationLabel = 'Inscrições';

    protected static ?string $modelLabel = 'Inscrição';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Identificação')
                                ->schema([
                                    Forms\Components\Hidden::make('user_criador')
                                        ->default(auth()->user()->id),

                                    Forms\Components\Select::make('acao_id')
                                        ->label('Ação/Evento')
                                        ->required(false)
                                        ->searchable()
                                        ->disabled(function ($context, $record) {
                                            if ($context == 'edit') {
                                                if ($record->inscricao_status != '1') {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }
                                        })
                                        ->reactive()
                                        ->live()
                                        ->options(Acao::query()
                                            ->where('status', '=', '2')
                                            ->whereDate('data_inicio_inscricoes', '<=', Carbon::now()->format('Y-m-d'))
                                            ->whereDate('data_fim_inscricoes', '>=', Carbon::now()->format('Y-m-d'))
                                            ->pluck('titulo', 'id')
                                            ->toArray())
                                        ->afterStateUpdated(function (Get $get) {
                                            //CONTAR INSCRIÇÕES
                                            $acao = Acao::find($get('acao_id'));
                                            $contInscAcao = Inscricao::where('acao_id', $get('acao_id'))->count();

                                            if ($contInscAcao >= $acao->vagas_total) {
                                                Notification::make()
                                                    ->title('ATENÇÃO')
                                                    ->warning()
                                                    ->color('danger')
                                                    ->body('Todas as vagas foram encerradas para esta Ação/Evento!')
                                                    ->persistent()
                                                    ->send();
                                            } else {
                                                //INFORMAR EXIGÊNCIA DE DOAÇÕES
                                                if ($acao->doacao == '1') {

                                                    Notification::make()
                                                        ->title('ATENÇÃO')
                                                        ->warning()
                                                        ->color('danger')
                                                        ->body('Para confirmar sua inscrição, entregue sua doação na DIEX: ' . $acao->tipo_doacao . '.')
                                                        ->persistent()
                                                        ->send();
                                                }
                                            }
                                        })
                                        ->rules([
                                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                $acao = Acao::find($get('acao_id'));
                                                $contInscAcao = Inscricao::where('acao_id', $get('acao_id'))->count();

                                                if ($contInscAcao >= $acao->vagas_total) {
                                                    $fail('Todas as vagas foram encerradas para esta Ação/Evento.');
                                                }
                                            },
                                        ]),

                                    Select::make('inscricao_tipo')
                                        ->label('Tipo de Inscrição')
                                        ->required(false)
                                        ->disabled(function ($context, $record) {
                                            if ($context == 'edit') {
                                                if ($record->inscricao_status != '1') {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }
                                        })
                                        ->reactive()
                                        ->options([
                                            '1' => 'Discente - IFPE - Campus Garanhuns',
                                            '2' => 'Servidor - IFPE - Campus Garanhuns',
                                            '3' => 'Externo - IFPE - Campus Garanhuns',
                                        ])

                                        ->afterStateUpdated(function (Get $get) {
                                            //CONTAR INSCRIÇÕES
                                            $acao = Acao::find($get('acao_id'));
                                            $contInsTipo = Inscricao::where('acao_id', $get('acao_id'))->where('inscricao_status', '!=', 3)->where('inscricao_tipo', $get('inscricao_tipo'))->count();

                                            if ($acao->cota == 1) {
                                                if ($get('inscricao_tipo') == 1) {
                                                    if ($contInsTipo >= ($acao->cota_discente))
                                                        Notification::make()
                                                            ->title('ATENÇÃO')
                                                            ->warning()
                                                            ->color('danger')
                                                            ->body('As vagas foram encerradas para os dicentes do Campus Garanhuns!')
                                                            ->persistent()
                                                            ->send();
                                                }

                                                if ($get('inscricao_tipo') == 2) {
                                                    if ($contInsTipo >= $acao->cota_servidor) {
                                                        Notification::make()
                                                            ->title('ATENÇÃO')
                                                            ->warning()
                                                            ->color('danger')
                                                            ->body('As vagas foram encerradas para os servidores do Campus Garanhuns!')
                                                            ->persistent()
                                                            ->send();
                                                    }
                                                }
                                                if ($get('inscricao_tipo') == 3) {
                                                    if ($contInsTipo >= $acao->cota_externo) {
                                                        Notification::make()
                                                            ->title('ATENÇÃO')
                                                            ->warning()
                                                            ->color('danger')
                                                            ->body('As vagas foram encerradas para os participantes externos!')
                                                            ->persistent()
                                                            ->send();
                                                    }
                                                }
                                            }
                                        })

                                        ->live()
                                        ->rules([
                                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {

                                                $acao = Acao::find($get('acao_id'));
                                                $contInsTipo = Inscricao::where('acao_id', $get('acao_id'))->where('inscricao_status', '!=', 3)->where('inscricao_tipo', $get('inscricao_tipo'))->count();
                                                // SE TIVER COTAS
                                                if ($acao->cota == 1) {
                                                   if ($get('inscricao_tipo') == 1) {
                                                        if ($contInsTipo >= ($acao->cota_discente)) {
                                                            $fail("As vagas foram encerradas para os dicentes do Campus Garanhuns");
                                                        }
                                                    }

                                                    if ($get('inscricao_tipo') == 2) {
                                                        if ($contInsTipo >= ($acao->cota_servidor)) {
                                                            $fail("As vagas foram encerradas para os servidores do Campus Garanhuns");
                                                        }
                                                    }

                                                    if ($get('inscricao_tipo') == 3) {
                                                        if ($contInsTipo >= ($acao->cota_externo)) {
                                                            $fail("As vagas foram encerradas para os participantes externos");
                                                        }
                                                    }
                                                }
                                            }
                                        ])
                                ]),
                            Wizard\Step::make('Dados Básicos')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            Forms\Components\Select::make('user_id')
                                                ->label('Servidor - IFPE - Campus Garanhuns')
                                                ->required(false)
                                                ->placeholder('Digite seu SIAPE')
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->searchable()
                                                ->getSearchResultsUsing(fn (string $search): array => User::where('username', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->name)
                                               // ->options(User::all()->pluck('name', 'id')->toArray())
                                                ->hidden(fn (Get $get) => $get('inscricao_tipo') == '1'  ||  $get('inscricao_tipo') == '3' ?? true),

                                            Forms\Components\Select::make('discente_id')
                                                ->label('Discente - IFPE - Campus Garanhuns')
                                                ->required(false)
                                                ->placeholder('Digite sua matrícula ')
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->searchable()
                                                ->getSearchResultsUsing(fn (string $search): array => Discente::where('username', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                                ->getOptionLabelUsing(fn ($value): ?string => Discente::find($value)?->name)
                                                //  ->options(Discente::all()->pluck('username', 'id')->toArray())
                                                ->hidden(fn (Get $get) => $get('inscricao_tipo') == '2'  ||  $get('inscricao_tipo') == '3' ?? true),
                                            Forms\Components\TextInput::make('nome')
                                                ->label('Nome')
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->maxLength(255)
                                                ->hidden(fn (Get $get) => $get('inscricao_tipo') == '1'  ||  $get('inscricao_tipo') == '2' ?? true),
                                            Forms\Components\TextInput::make('cpf')
                                                ->mask('999.999.999-99')
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->label('CPF'),
                                            Forms\Components\TextInput::make('telefone')
                                                ->required(false)
                                                ->mask('(99)99999-9999')
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->tel(),
                                            Forms\Components\TextInput::make('email')
                                                ->email()
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->maxLength(255),
                                        ]),
                                ]),
                            Wizard\Step::make('Outras Informações')
                                ->schema([
                                    Grid::make()
                                        ->schema([
                                            Forms\Components\TextInput::make('instituicao_origem')
                                                ->label('Instituição de Origem')
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->maxLength(255),
                                            Forms\Components\Select::make('escolaridade')
                                                ->label('Escolaridade')
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->searchable()
                                                ->options([
                                                    '1' => 'Não Alfabetizado',
                                                    '2' => 'Fundamental I - Incompleto',
                                                    '3' => 'Fundamental I - Completo',
                                                    '4' => 'Fundamental II - Incompleto',
                                                    '5' => 'Fundamental II - Completo',
                                                    '6' => 'Ensino Médio - Incompleto',
                                                    '7' => 'Ensino Médio - Completo',
                                                    '8' => 'Graduação - Incompleto',
                                                    '9' => 'Graduação - Completo',
                                                    '10' => 'Pós-Graduado',
                                                    '11' => 'Mestrado',
                                                    '12' => 'Doutorado',


                                                ]),
                                            Forms\Components\TextInput::make('naturalidade')
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->maxLength(255),
                                            Forms\Components\DatePicker::make('data_nascimento')
                                                ->label('Data de Nascimento')
                                                ->date()
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->reactive()
                                                ->required(true)
                                                ->afterStateUpdated(function ($set, $state) {

                                                    $set('idade', Carbon::parse($state)->age);

                                                    //  return Carbon::parse($state)->age;
                                                })
                                                ->live(onBlur: true),
                                            Forms\Components\Hidden::make('idade')
                                                ->default(18)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->live(),
                                            Forms\Components\TextInput::make('responsavel_nome')
                                                ->label('Nome do responsável')
                                                ->required(false)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->maxLength(255)
                                                ->required()
                                                ->hidden(fn (Get $get) => $get('idade') > '17' ?? true),
                                            Forms\Components\TextInput::make('responsavel_grau')
                                                ->required(true)
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->hidden(fn (Get $get) => $get('idade') > '17' ?? true),
                                            Radio::make('cor_raca')
                                                ->label('Cor/Raça:')
                                                ->disabled(function ($context, $record) {
                                                    if ($context == 'edit') {
                                                        if ($record->inscricao_status != '1') {
                                                            return true;
                                                        } else {
                                                            return false;
                                                        }
                                                    }
                                                })
                                                ->inline()
                                                ->options([
                                                    '1' => 'Branca',
                                                    '2' => 'Preta',
                                                    '3' => 'Parda',
                                                    '4' => 'Amarela',
                                                    '5' => 'Indígena',
                                                    '6' => 'Não Declarar',
                                                ])->columnSpanFull(),
                                            Forms\Components\Hidden::make('inscricao_status')
                                                ->default(1),
                                            Forms\Components\Hidden::make('aprovacao_status')
                                                ->default(1),
                                        ]),

                                ]),
                        ])
                        /*->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                        >
                            Enviar
                        </x-filament::button>
                    BLADE))) */
                    ]),

            ])->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Inscrição'),
                Tables\Columns\TextColumn::make('acao.titulo')
                    ->label('Ação/Evento'),
                Tables\Columns\IconColumn::make('inscricao_status')
                    ->alignCenter()
                    ->label('Status - Inscrição')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-m-clock',
                        '2' => 'heroicon-m-check',
                        '3' => 'heroicon-m-trash',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('aprovacao_status')
                    ->alignCenter()
                    ->label('Status - Aprovação')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-m-clock',
                        '2' => 'heroicon-m-academic-cap',
                        '3' => 'heroicon-m-academic-cap',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'warning',
                        '2' => 'success',
                        '3' => 'danger',
                    }),
                Tables\Columns\SelectColumn::make('inscricao_tipo')
                    ->label('Tipo de Inscrição')
                    ->disabled()
                    ->options([
                        '1' => 'Discente - IFPE - Campus Garanhuns',
                        '2' => 'Servidor - IFPE - Campus Garanhuns',
                        '3' => 'Externo - IFPE - Campus Garanhuns',
                    ]),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Servidor'),
                Tables\Columns\TextColumn::make('discente.name'),
                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF'),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Externo'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->disabled(function ($record) {
                        if ($record->inscricao_status != '1') {
                            return true;
                        } else {
                            return false;
                        }
                    }),
                Tables\Actions\Action::make('Imprimir_inscricao')
                    ->label('Imprimir Comprovante')
                    ->url(fn (Inscricao $record): string => route('imprimirInscricao', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('Imprimir_certificdao')
                    ->label('Imprimir Certificado')
                    ->disabled(function ($record) {
                        if ($record->aprovacao_status == 2) {
                            return false;
                        } else {
                            return true;
                        }
                    })
                    ->url(fn (Inscricao $record): string => route('imprimirCertificadoParticipante', $record))
                    ->openUrlInNewTab(),

            ])
            ->bulkActions([])
            ->headerActions([])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInscricaos::route('/'),
            'create' => Pages\CreateInscricao::route('/create'),
            'edit' => Pages\EditInscricao::route('/{record}/edit'),
        ];
    }
}
