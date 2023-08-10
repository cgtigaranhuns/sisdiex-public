<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConteudoProgramatico extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'acao_id',
        'ministrante',
        'cpf',
        'email',
        'ementa',
        'data_inicio',
        'data_termino',
        'carga_horaria',
        'certifiado_cod',
        'certificado_data',
        'certificado_status',
    ];

    public function Acao() {
        return $this->belongsTo(Acao::class);
    }
}
