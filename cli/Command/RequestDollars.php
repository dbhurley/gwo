<?php
/**
 * Joomla! Framework Sample Application
 *
 * @copyright  Copyright (C) 2014 David Hurley. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Money\CLI\Command;

use Money\CLI\Application;

/**
 * CLI Command to ask a user how many Dollars they would like
 *
 * @since  1.0
 */
class RequestDollar
{
	/**
	 * Application object
	 *
	 * @var    Application
	 * @since  1.0
	 */
	private $app;

	/**
	 * Class constructor
	 *
	 * @param   Application  $app  Application object
	 *
	 * @since   1.0
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Execute the command
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		// Ask the user how many Dollars they would like
		$this->app->out('How many Dollars would you like?');

		$Dollars = (int) $this->app->in();

		// Send a message ;-)
		if ($Dollars === 0)
		{
			$this->app->out('You wanted no Dollar, what a bummer :-(');
		}
		elseif ($Dollars === 1)
		{
			$this->app->out('Only one Dollar?  Lame!');
		}
		else
		{
			$this->app->out($Dollars . ' Dollars?  OK!');
		}
	}
}
