<?php

namespace App\Models\Location;

use App\Traits\LogsAll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    use LogsAll;

    public $table = 'city';

    protected $fillable = [
        'id',
        'state_id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
