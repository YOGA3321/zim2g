<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveFile extends Model
{
    protected $fillable = [
        'archive_id',
        'user_id',
        'file_name',
        'google_drive_file_id',
        'google_drive_link'
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
