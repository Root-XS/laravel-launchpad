<?php

namespace Xs\Console\Commands;

use Illuminate\Console\Command;
use Log;

/**
 * Cron - sends user message notifications.
 */
class CronRotateLogs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string $signature
	 */
	protected $signature = 'cronRotateLogs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Log rotator cron job.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$strLogFile = storage_path() . '/logs/laravel.log';
		$strArchivePath = storage_path() . '/logs/archives/';
		$strArchiveFile = $strArchivePath . date('Y-m-d', time() - 24 * 60 * 60) . '.log';
		exec(
			'mkdir -p ' . $strArchivePath
				. ' && cp ' . $strLogFile . ' '. $strArchiveFile
				. ' && truncate -s 0 ' . $strLogFile
		);

		Log::info('Logs rotated.');
	}

}
