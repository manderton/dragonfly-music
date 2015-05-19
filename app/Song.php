<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class Song extends Model {

	protected $fillable = ['user_id', 'artist_id', 'album_id', 'name', 'filename', 'track_number'];

    public function album()
    {
        return $this->belongsTo('Dragonfly\Album');
    }

    public function artist()
    {
        return $this->belongsTo('Dragonfly\Artist');
    }

    public function playlists()
    {
        return $this->belongsToMany('Dragonfly\Playlist');
    }
}
