<?php namespace Dragonfly\Http\Controllers;

use Dragonfly\Http\Requests;
use Dragonfly\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Dragonfly\Playlist;
use Dragonfly\PlaylistSong;

class PlaylistsController extends Controller {

	public function add_song($playlist, $song)
	{
		return PlaylistSong::create([
			'playlist_id' => str_replace("playlist-", "", $playlist),
			'song_id' => str_replace("song-", "", $song),
		]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get_list()
	{
		$playlists = Playlist::where('user_id', \Auth::user()->id)
							->orderBy('name', 'asc')
							->get();

		$playlists_and_counts = [];
		foreach ($playlists as $playlist) {
			$playlists_and_counts[$playlist->id]['name'] = $playlist->name;
			$playlists_and_counts[$playlist->id]['count'] = PlaylistSong::where('playlist_id', $playlist->id)->count();
		}
		return response()->json(['playlists' => $playlists_and_counts]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$playlists = Playlist::where('user_id', \Auth::user()->id)
							->orderBy('name', 'asc')
							->paginate(25);

		return view('playlists.index')->with(compact('playlists'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		\Log::info('new playlist being created...');
		\Log::info('name: ' . $request->input('name'));

		if ($request->input('name')) {
			$playlist = Playlist::create([
				'user_id' => \Auth::user()->id,
				'name' => $request->input('name'),
			]);
			return response()->json(['result' => 'success', 'id' => $playlist->id]);
		}
		return response()->json(['result' => 'error']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$playlist = Playlist::with('songs')->where('id', $id)->first();

		// need to pass songs separately for the player:
		return view('playlists.show')->with(compact('playlist'))->with('songs', $playlist->songs);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		dd(Playlist::find($id));
	}

}
