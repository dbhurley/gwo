<?php
/**
 * Joomla! Framework Sample Application
 *
 * @copyright  Copyright (C) 2014 David Hurley. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Money\CLI;

use Money\CLI\Command\RequestDollar;
use Money\CLI\Command\UpdateServer;

use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\Output\Processor\ColorProcessor;

/**
 * CLI application for the Framework Sample Application
 *
 * @since  1.0
 */
class Application extends AbstractCliApplication
{
	/**
	 * Class constructor
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		parent::__construct();

		// Set up the output processor
		$this->getOutput()->setProcessor(new ColorProcessor);
	}

	/**
	 * Method to run the application routines.  Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doExecute()
	{
		// If --updateserver option provided, run the update routine
		if ($this->input->getBool('updateserver', false))
		{
			(new UpdateServer($this))->execute();
		}
		// Otherwise execute the normal routine
		else
		{
			(new RequestDollar($this))->execute();
		}

		$this->out('Finished!');
	}

	/**
	 * Execute a command on the server.
	 *
	 * @param   string  $command  The command to execute.
	 *
	 * @return  string  Return data from the command
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function runCommand($command)
	{
		$lastLine = system($command, $status);

		if ($status)
		{
			// Command exited with a status != 0
			if ($lastLine)
			{
				$this->out($lastLine);

				throw new \RuntimeException($lastLine);
			}

			$this->out('An unknown error occurred');

			throw new \RuntimeException('An unknown error occurred');
		}

		return $lastLine;
	}
}
