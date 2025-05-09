<?php

namespace TestVendor\UsersList\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = ['user_id', 'number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
