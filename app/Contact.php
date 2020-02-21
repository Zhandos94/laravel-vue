<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $guarded = [];

    protected $dates = ['birthday'];

    public function path()
    {
        return '/contacts/' . $this->id;
    }

    public function setBirthdayAttribute($birthday)
    {
        $this->attributes['birthday'] = Carbon::parse($birthday);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
