<?php
/**
 * Joomla! Framework Sample Application
 *
 * @copyright  Copyright (C) 2014 David Hurley. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

// Set error reporting for development
error_reporting(-1);

// Application constants
define('JPATH_ROOT',      dirname(__DIR__));
define('JPATH_TEMPLATES', JPATH_ROOT . '/templates');

// Ensure we've initialized Composer
if (!file_exists(JPATH_ROOT . '/vendor/autoload.php'))
{
	header('HTTP/1.1 500 Internal Server Error', null, 500);
	require JPATH_TEMPLATES . '/composerError.php';

	exit(500);
}

require JPATH_ROOT . '/vendor/autoload.php';

// Execute the application
(new Money\Application)->execute();
