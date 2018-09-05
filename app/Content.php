<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['name', 'order', 'status'];

    public function Translations()
    {
        return $this->hasMany('App\ContentTranslation');
    }

    public function Gallery()
    {
        return $this->hasMany('App\ContentImage');
    }
}
