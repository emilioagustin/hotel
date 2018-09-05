<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentImage extends Model
{
    protected $fillable = ['content_id', 'image_path'];

    public function content()
    {
        return $this->belongsTo('App\Content');
    }
}
