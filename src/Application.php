<?php
/**
 * Joomla! Framework Sample Application
 *
 * @copyright  Copyright (C) 2014 David Hurley. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Money;

use Joomla\Application\AbstractWebApplication;
use Joomla\Router\Router;

/**
 * Web application class
 *
 * @since  1.0
 */
final class Application extends AbstractWebApplication
{
	/**
	 * Method to run the application routines
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Instantiate the router
			$router = (new Router($this->input))
				->setControllerPrefix('\\Money')
				->setDefaultController('\\Controller\\DefaultController')
				->addMap('/Dollars/:number', '\\Controller\\DollarController');


			// Fetch the controller
			/* @type  \Joomla\Controller\AbstractController  $controller */
			$controller = $router->getController($this->get('uri.route'));

			// Inject the application into the controller and execute it
			$controller->setApplication($this)->execute();
		}
		catch (\Exception $exception)
		{
			// Set the appropriate HTTP response
			switch ($exception->getCode())
			{
				case 404 :
					$this->setHeader('HTTP/1.1 404 Not Found', 404, true);

					break;

				case 500 :
				default  :
					$this->setHeader('HTTP/1.1 500 Internal Server Error', 500, true);

					break;
			}

			// Render the message based on the format
			switch (strtolower($this->input->getWord('format', 'html')))
			{
				case 'json' :
					$data = [
						'code'    => $exception->getCode(),
						'message' => $exception->getMessage(),
						'error'   => true
					];

					$this->setBody(json_encode($data));

					break;

				case 'html' :
				default :
					// Get the exception template
					ob_start();
					include JPATH_TEMPLATES . '/exception.php';
					$template = ob_get_clean();

					// Replace the placeholders with content
					$this->setBody(
						str_replace(['<exceptionCode />', '<exceptionMessage />'], [$exception->getCode(), $exception->getMessage()], $template)
					);
			}
		}

		// If an HTML view, need to render the template
		if ($this->input->getWord('format', 'html') == 'html')
		{
			// Get the base template
			ob_start();
			include JPATH_TEMPLATES . '/index.php';
			$template = ob_get_clean();

			// Replace the component tag with the body contents, and set the result as the body
			$this->setBody(str_replace('<bodyContent />', $this->getBody(), $template));
		}
	}

	/**
	 * Custom initialisation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		// Set the MIME for the application based on format
		switch (strtolower($this->input->getWord('format', 'html')))
		{
			case 'json' :
				$this->mimeType = 'application/json';

				break;

			// Don't need to do anything for the default case
			default :
				break;
		}
	}
}
