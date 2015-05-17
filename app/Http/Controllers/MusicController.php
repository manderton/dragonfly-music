<?php namespace Dragonfly\Http\Controllers;

use Dragonfly\Http\Requests;
use Dragonfly\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use PhpId3\Id3TagsReader;

use Dragonfly\Artist;
use Dragonfly\Song;
use Dragonfly\Album;

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

				$tags = $this->readTags($filepath . $filename);
				foreach ($tags as $k => $v) {
					\Log::info($k . ": " . $v);
				}

				if ($artist_id = $this->writeArtist($tags['artist'])) {
					if ($album_id = $this->writeAlbum($tags['album'], $artist_id)) {
						$song_id = $this->writeSong($tags, $filename, $artist_id, $album_id);
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

	private function readTags($fullpath)
	{
		\Log::info('reading tags for : ' . $fullpath);

		$id3 = new Id3TagsReader(fopen($fullpath, "rb"));
		$id3->readAllTags();
		$tags = $id3->getId3Array();

		\Log::info(var_export($tags, true));
		$tagdata = [];
		foreach($id3->getId3Array() as $key => $value) {
			switch ($key) {
				case 'TIT2':
					$tagdata['song'] = $this->cleanString($value['body']);
					break;
				case 'TPE1':
					$tagdata['artist'] = $this->cleanString($value['body']);
					break;
				case 'TALB':
					$tagdata['album'] = $this->cleanString($value['body']);
					break;
				case 'TRCK':
					$tagdata['track_number'] = $this->cleanString($value['body']);
					break;
				default:
					break;
			}
		}
		\Log::info(var_export($tagdata, true));
		\Log::info('song: ' . $tagdata['song']);

		return $tagdata;
	}

	private function cleanString($string)
	{
		$letters = [' ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
		$numbers = ['1','2','3','4','5','6','7','8','9','0'];
		$specials = ['.','-',',','$','@','#','%','&','^','!','*','(',')','/',':',';','+'];

		$new_string = "";
		foreach (str_split($string) as $char) {
			if (in_array(strtoupper($char), $letters) || in_array($char, $numbers)) {
				$new_string .= $char;
			}
		}
		return trim($new_string);
	}

	private function writeArtist($artist)
	{
		\Log::info('about to write artist: ' . $artist);
		$db_artist = Artist::where('user_id', \Auth::user()->id)->where('name', $artist)->first();
		if (!$db_artist) {
			\Log::info('artist does not exist... creating...');
			$db_artist = Artist::create([
				'user_id' => \Auth::user()->id,
				'name' => $artist,
			]);
		}
		\Log::info('returning artist id: ' . $db_artist->id);
		return $db_artist->id;
	}

	private function writeAlbum($album, $artist_id)
	{
		\Log::info('writing album to db...');
		$db_album = Album::where('user_id', \Auth::user()->id)
						->where('name', $album)
						->where('artist_id', $artist_id)
						->first();
		if (!$db_album) {
			$db_album = Album::create([
				'user_id' 	=> \Auth::user()->id,
				'artist_id' => $artist_id,
				'name' 		=> $album,
			]);
		}
		\Log::info('returning album id: ' . $db_album->id);
		return $db_album->id;
	}

	public function writeSong($data, $filename, $artist_id, $album_id)
	{
		$db_song = Song::where('user_id', \Auth::user()->id)
						->where('name', $data['song'])
						->where('artist_id', $artist_id)
						->where('album_id', $album_id)
						->first();
		if (!$db_song) {
			$db_song = song::create([
				'user_id' 	=> \Auth::user()->id,
				'album_id' 	=> $album_id,
				'artist_id' => $artist_id,
				'name' 		=> $data['song'],
				'track_number' 		=> $data['track_number'],
				'filename' 	=> $filename,
			]);
		}
		\Log::info('returning song id: ' . $db_song->id);
		return $db_song->id;
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
