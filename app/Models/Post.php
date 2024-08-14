<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function media()
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }
}
