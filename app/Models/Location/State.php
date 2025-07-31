<?php

namespace App\Models\Location;

use App\Traits\LogsAll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    use LogsAll;

    public $table = 'state';

    protected $fillable = [
        'id',
        'country_id',
        'name',
        'code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
