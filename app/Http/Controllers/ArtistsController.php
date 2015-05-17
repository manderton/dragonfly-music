<?php namespace Dragonfly\Http\Controllers;

use Dragonfly\Http\Requests;
use Dragonfly\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Dragonfly\Artist;
use Dragonfly\Song;
use Dragonfly\Album;

class ArtistsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$artists = Artist::where('user_id', \Auth::user()->id)->get();
		return view('artists.index')->with(compact('artists'));
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
		$artist = Artist::find($id);
		$songs = Song::where('artist_id', $id)->paginate(5);
		$albums = Album::where('artist_id', $id)->paginate(5);
		return view('artists.show')->with(compact('artist','songs','albums'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function songs($id)
	{
		$artist = Artist::find($id);
		$songs = Song::where('artist_id', $id)->paginate(25);
		return view('artists.songs')->with(compact('artist','songs'));
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
