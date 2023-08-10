<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'mail',
        'guid',
        'domain',
        'password',

    ];
}
