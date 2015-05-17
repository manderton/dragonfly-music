<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model {

	protected $fillable = ['user_id', 'name'];

    public function songs()
    {
        return $this->hasMany('Dragonfly\Song');
    }

    public function albums()
    {
        return $this->hasMany('Dragonfly\Album');
    }
}
