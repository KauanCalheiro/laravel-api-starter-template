<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;

    public $table = 'bas_estado';

    protected $fillable = [
        'nome',
        'sigla',
        'ref_pais',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function pais()
    {
        $pais = new Pais();

        return $this->belongsTo(
            $pais::class,
            'ref_pais',
            $pais->getKeyName(),
        );
    }
}
