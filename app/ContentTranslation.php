<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    protected $fillable = ['content_id', 'language', 'title', 'body'];
    
    public function content()
    {
        return $this->belongsTo('App\Content');
    }
}
