<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;

    public $table = 'bas_pais';

    protected $fillable = [
        'nome',
        'sigla',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
