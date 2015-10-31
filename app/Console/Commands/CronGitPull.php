<?php

namespace Xs\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Xs\Notify;

/**
 * Cron - sends user message notifications.
 */
class CronGitPull extends Command {

	/**
	 * The console command name.
	 *
	 * @var string $signature
	 */
	protected $signature = 'cronGitPull {--migrate : Whether db migrations should be run}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Git pull cron job.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		// Build the command & the log message
		$strCmd = 'git pull && git submodule update && ' . env('COMPOSER_CMD', 'composer') . ' install';
		$strMessage = "\nRan git pull";
		if ($this->option('migrate')) {
			$strCmd .= ' && php artisan migrate --force';
			$strMessage .= ' and artisan migrate';
		}
		$strMessage .= '.';

		// Run the command
		$aOutput = [&$strMessage];
		exec($strCmd, $aOutput, $iResult);

		// Build response
		if (0 === $iResult) {
			$strMethod = 'info';
		} else {
			$strMethod = 'error';
			$strMessage .= ' Error: ' . $iResult;
			Notify::mail('admin@rootxs.org', '[Xs Cron Error]', null, ['strMessage' => implode('<br>', $aOutput)]);
		}
		echo implode("\n", $aOutput);
		Log::$strMethod($strMessage);
		$this->$strMethod($strMessage);
	}

}
