<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'domain', 'user_id', 'api_key_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class);
    }
}
