<?php

namespace App\Models\Location;

use App\Traits\LogsAll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    use LogsAll;

    public $table = 'country';

    protected $fillable = [
        'id',
        'name',
        'code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
