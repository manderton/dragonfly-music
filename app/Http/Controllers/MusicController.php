<?php namespace Dragonfly\Http\Controllers;

use Dragonfly\Http\Requests;
use Dragonfly\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

use Dragonfly\Artist;
use Dragonfly\Song;
use Dragonfly\Album;
use Dragonfly\Music;

class MusicController extends Controller {

	public function upload(Route $route)
	{
		return view('music.upload')->with(compact('route'));
	}

	public function do_upload(Request $request)
	{
		\Log::info('file uploaded: ');
		\Log::info(var_export($request->all(), true));

		if ($request->hasFile('file')) {
			\Log::info('has file...');
			$filename = $request->file('file')->getClientOriginalName();
			$filepath = storage_path() . "/music/";
			if ($request->file('file')->move($filepath, $filename)) {
				\Log::info('file moved successfully to: ' . $filepath);

				$tags = Music::readTags($filepath . $filename);
				foreach ($tags as $k => $v) {
					\Log::info($k . ": " . $v);
				}

				if ($artist_id = $this->writeArtist($tags['artist'], \Auth::user()->id)) {
					if ($album_id = $this->writeAlbum($tags['album'], $artist_id, \Auth::user()->id)) {
						$song_id = $this->writeSong($tags, \Auth::user()->id, $filename, $artist_id, $album_id);
						$this->moveToS3($filename);
					}
				}

				// \Log::info(var_export($tags, true));
			} else {
				\Log::error('unable to move file!');
			}
		}
	}

	private function moveToS3($filename)
	{
		\Log::info('writing song to S3...');

		$local 	= \Storage::disk('local');

		$song 	= $local->get($filename);

		$s3 	= \Storage::disk('s3');

		$s3->put($filename, $song);

		\Log::info('deleting local copy...');
		$local->delete($filename);
	}


	public function album($id)
	{

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		view()->share('url', \Config::get('dragonfly.s3_base'));

		$songs = Song::with('album','artist')
						->where('user_id', \Auth::user()->id)
						->orderBy('name', 'asc')
						->paginate(10);
						// dd($songs);
		return view('music.index')->with(compact('songs'));
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
		//
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
