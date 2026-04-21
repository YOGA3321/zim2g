<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'user_id', 
        'zi_sub_component_id', 
        'year', 
        'file_name', 
        'google_drive_file_id', 
        'google_drive_link', 
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subComponent()
    {
        return $this->belongsTo(ZiSubComponent::class, 'zi_sub_component_id');
    }
}
