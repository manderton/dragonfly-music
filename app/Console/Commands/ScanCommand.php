<?php namespace Dragonfly\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Dragonfly\Song;
use Dragonfly\Music;

class ScanCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'music:scan';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Scan S3 bucket for new music';
	protected $s3;
	protected $local;

	// TODO this sucks...
	protected $user = 1;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->s3 = \Storage::disk('s3');
		$this->local = \Storage::disk('local');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('firing...');

		$songs = $this->s3->allFiles();

		foreach ($songs as $song) {

			// do we know about the song already?
			if (!$dbsong = Song::where('filename', $song)->first()) {
				$this->info('******************************************');
				$this->info('fetching new song: ' . $song);

				$this->handleSong($song);
			} else {
				$this->comment('already know about ' . $song . '.... skipping...');
			}
		}
		$this->info('DONE!');
	}

	private function handleSong($song)
	{
		$this->comment('processing new song: ' . $song);

		// download it...
		$content = $this->s3->get($song);
		$this->local->put($song, $content);

		if ($songdata = Music::readTags(storage_path() . "/music/" . $song)) {
			$this->logSong($song, $songdata);

		} else {
			$this->error('not enough data about song to write to db...');
		}

		// delete it...
		$this->info('deleting local copy...');
		$this->local->delete($song);
	}

	private function logSong($filename, $songdata)
	{
		if ($artist_id = Music::writeArtist($songdata['artist'], $this->user)) {
			$this->comment('successfully wrote artist to db: ' . $artist_id);

			if ($album_id = Music::writeAlbum($songdata['album'], $artist_id, $this->user)) {
				$this->comment('successfully wrote album to db: ' . $album_id);

				$song_id = Music::writeSong($songdata, $this->user, $filename, $artist_id, $album_id);
				$this->comment('successfully wrote song to db: ' . $song_id);

				$this->info('++++++++++++++++++++++++++++');
				$this->comment('song data successfully written to database...');
				$this->info('++++++++++++++++++++++++++++');

				return true;
			}
		}
		return false;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			// ['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			// ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
