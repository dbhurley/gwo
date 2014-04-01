<?php
/**
 * Joomla! Framework Sample Application
 *
 * @copyright  Copyright (C) 2014 David Hurley. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Money\Controller;

use Joomla\Controller\AbstractController;

/**
 * Default controller class for the application
 *
 * @since  1.0
 */
class DefaultController extends AbstractController
{
	/**
	 * The default layout for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'dashboard';

	/**
	 * State object to inject into the model
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.0
	 */
	protected $modelState = null;

	/**
	 * Execute the controller
	 *
	 * This is a generic method to execute and render a view and is not suitable for tasks
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		// Get the input
		$input = $this->getInput();

		// Get some data from the request
		$vName   = $input->getWord('view', $this->defaultView);
		$vFormat = strtolower($input->getWord('format', 'html'));

		$input->set('view', $vName);

		$vClass = '\\Money\\View\\' . ucfirst($vName) . '\\' . ucfirst($vName) . ucfirst($vFormat) . 'View';
		$mClass = '\\Money\\Model\\' . ucfirst($vName) . 'Model';

		// If a model doesn't exist for our view, revert to the default model
		if (!class_exists($mClass))
		{
			$mClass = '\\Money\\Model\\DefaultModel';

			// If there still isn't a class, panic.
			if (!class_exists($mClass))
			{
				throw new \RuntimeException(sprintf('No model found for view %s', $vName), 500);
			}
		}

		// Make sure the view class exists, otherwise revert to the default
		if (!class_exists($vClass))
		{
			$vClass = '\\Money\\View\\Default' . ucfirst($vFormat) . 'View';

			// If there still isn't a class, panic.
			if (!class_exists($vClass))
			{
				throw new \RuntimeException(sprintf('A view class was not found for the %s format.', $vFormat), 500);
			}
		}

		// Register the templates paths for the view
		$paths = new \SplPriorityQueue();

		// Priority 2 - Base templates folder
		$paths->insert(JPATH_TEMPLATES, 1);

		// Priority 1 - View specific folder, if it exists
		if (is_dir(JPATH_TEMPLATES . '/' . $vName . '/'))
		{
			$paths->insert(JPATH_TEMPLATES . '/' . $vName . '/', 2);
		}

		// If the controller has anything to inject into the model, do it here via the state
		$this->initializeModel();

		// Initialize the view class with its dependencies
		$view = new $vClass(new $mClass($this->modelState), $paths);

		// Set the layout for HTML views
		if ($vFormat == 'html')
		{
			$view->setLayout(strtolower($input->getWord('layout', 'default')));
		}

		// Render our view.
		try
		{
			$this->getApplication()->setBody($view->render());

			return true;
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException(sprintf('Error: ' . $e->getMessage()), $e->getCode());
		}
	}

	/**
	 * Method to initialize data to inject into the model via the state
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeModel()
	{
		return;
	}
}
