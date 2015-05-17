<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model {

	protected $fillable = ['user_id','name'];

    public function songs()
    {
        return $this->belongsToMany('Dragonfly\Song', 'playlist_songs');
    }

}
