<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAcao extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nome'
    ];

    public function Acao() {
        return $this->hasMany(Acao::class);
    }
}
