<?php namespace Dragonfly\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use PhpId3\Id3TagsReader;

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

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('firing...');

		$s3 = \Storage::disk('s3');
		$local = \Storage::disk('local');

		$songs = $s3->allFiles();

		foreach ($songs as $song) {
			$url = \Config::get('dragonfly.s3_base') . urlencode($song);
			$this->comment('getting the song at url: ' . $url);

			// download it...
			$content = $s3->get($song);
			$local->put("tmp-" . $song, $content);

			// write a local tmp:
			$id3 = new Id3TagsReader(fopen(storage_path() . "/music/tmp-" . $song, "rb"));
			$id3->readAllTags();
			$tags = $id3->getId3Array();
			foreach ($tags as $k => $v) {
				if ($k != 'APIC') {
					foreach ($v as $x => $y) {
						$this->info($x . ": " . $y);
					}
				}
			}
		}
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
