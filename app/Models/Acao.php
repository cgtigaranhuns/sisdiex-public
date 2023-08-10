<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acao extends Model
{
    use HasFactory;

    protected $fillable = [

        'titulo',
        'user_id',
        'area_conhecimento_id',
        'area_tematica_id',
        'area_extensao_id',
        'tipo_acao_id',
        'atividade_relativa',
        'publico_alvo',
        'vagas_total',
        'vagas_externa',
        'local',
        'data_inicio',
        'data_encerramento',
        'hora_inicio',
        'hora_encerramento',
        'dias_semana',
        'carga_hr_semanal',
        'carga_hr_total',
        'periocidade',
        'modalidade',
        'turno',
        'duracao_aula',
        'criterio_aprovacao',
        'frequencia_minima',
        'media_aprovacao',
        'forma_avaliacao',
        'requisitos',
        'justificativa',
        'objetivo_geral',
        'objetivo_especifico',
        'motodologia',
        'bibliografia',
        'outras_informacoes',
        'status',
        'data_inicio_inscricoes',
        'data_fim_inscricoes',
        'doacao',
        'tipo_doacao',
        'cota',
        'cota_servidor',
        'cota_discente',
        'cota_externo',

    ];

        protected $casts = [
            'dias_semana' => 'array',
        ];

        public function ConteudoProgramatico() {
            
            return $this->hasMany(ConteudoProgramatico::class);
        }
}
