<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class PlaylistSong extends Model {

	protected $fillable = ['playlist_id','song_id'];

}
