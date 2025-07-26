<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
{
    use SoftDeletes;

    public $table = 'bas_cidade';

    protected $fillable = [
        'nome',
        'ref_estado',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function estado()
    {
        $estado = new Estado();

        return $this->belongsTo(
            $estado::class,
            'ref_estado',
            $estado->getKeyName(),
        );
    }
}
