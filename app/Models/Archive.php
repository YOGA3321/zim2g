<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'user_id', 
        'zi_component_id', 
        'year', 
        'google_drive_folder_id',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function component()
    {
        return $this->belongsTo(ZiComponent::class, 'zi_component_id');
    }

    public function files()
    {
        return $this->hasMany(ArchiveFile::class);
    }
}
