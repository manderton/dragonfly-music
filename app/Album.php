<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class Album extends Model {

	protected $fillable = ['user_id', 'artist_id', 'name'];

    public function artist()
    {
        return $this->belongsTo('Dragonfly\Artist');
    }
}
