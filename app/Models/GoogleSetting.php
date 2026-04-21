<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSetting extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'google_email',
        'expires_at',
        'root_folder_id'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
