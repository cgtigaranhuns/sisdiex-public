<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    use HasFactory;

    protected $fillable = [
        'acao_id',
        'inscricao_tipo',
        'user_id',
        'discente_id',
        'cpf',
        'nome',
        'telefone',
        'email',
        'instituicao_origem',
        'escolaridade',
        'data_nascimento',
        'naturalidade',
        'cor_raca',
        'inscricao_status',
        'aprovacao_status',
        'nota',
        'obs',
        'motivo_reprovacao',
        'certificado_cod',
        'certificado_data',
        'responsavel_nome',
        'responsaval_cpf',
        'responsavel_grau',
        'user_criador',
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function Discente() {
        return $this->belongsTo(Discente::class);
    }

    public function Acao(){
        return $this->belongsTo(Acao::class);
    }
}
