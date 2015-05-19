<?php namespace Dragonfly\Http\Controllers;

use Dragonfly\Http\Requests;
use Dragonfly\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Dragonfly\Album;
use Dragonfly\Song;
use Dragonfly\Gracenote;

class AlbumsController extends Controller {

	public function artist($id)
	{
		$albums = Album::where('user_id', \Auth::user()->id)->where('artist_id', $id)->get();
		return view('albums.index')->with(compact('albums'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$albums = Album::with('artist')
						->where('user_id', \Auth::user()->id)
						->orderBy('name', 'asc')
						->get();
		return view('albums.index')->with(compact('albums'));
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
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if ($album = Album::with('artist')
						->where('user_id', \Auth::user()->id)
						->where('id', $id)
						->first()) {
			$songs = Song::where('album_id', $id)
						->orderBy('track_number','asc')
						->orderBy('name', 'asc')
						->get();

			if ($gn = new Gracenote(\Auth::user()->id)) {
				\Log::info('got a Gracenote instance...');
				$gn_artist = $gn->track_search($songs[0]->name, $album->artist->name, $album->name);
				view()->share(compact('gn_artist'));
			}
			return view('albums.show')->with(compact('album','songs'));
		}
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
		//
	}

}
