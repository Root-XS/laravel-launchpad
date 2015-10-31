<?php

namespace Xs\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Auth, Form, Helper, Honeypot, Redirect, Request, Route, View;

abstract class Controller extends BaseController
{
	use DispatchesJobs, ValidatesRequests;

	/**
	 * Global properties.
	 *
	 * @property View $layout
	 * @property array $aViewless List of GET methods that do not have a View
	 *                            (usu. redirects to something else)
	 * @property bool $bHasJS Does this controller have an associated JS file?
	 * @property string $strAction The current controller action.
	 * @property string $strController The current controller.
	 */
	protected $layout;
	protected $aViewless = [];
	protected $bHasJS = true;
	protected $strAction = '';
	protected $strController = '';

	/**
	 * Extends parent::callAction()
	 *
	 * Trigger misc global setup.
	 *
	 * Any controller can define a general() method
	 * for setup common to all actions.
	 *
	 * @param string  $strMethod
	 * @param array   $aParameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($strMethod, $aParameters)
	{
		$this->setupLayout();

		// Call the action if $this->general() doesn't suggest otherwise
		$mResult = null;
		if (method_exists($this, 'general'))
			$mResult = $this->general();
		// return $mResult ? $mResult : parent::callAction($strMethod, $aParameters);
		if (!$mResult)
			$mResult = parent::callAction($strMethod, $aParameters);

		if (!$mResult && $this->layout)
			return $this->layout;
		else
			return $mResult;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		$aParts = explode('@', Route::currentRouteAction());
		$this->strController = strtolower(Helper::camelToDashed(
				str_replace('\\', '.', str_replace(['Xs\Http\Controllers\\', 'Controller'], '', $aParts[0]))
		));
		$this->strAction = strtolower(Helper::camelToDashed(substr($aParts[1], strlen(Request::method()))));

		// Create default view mapping
		if (Request::isMethod('get') && !Request::ajax() && !in_array($this->strAction, $this->aViewless)) {
			$this->layout = View::make($this->strController . '.' . $this->strAction, [
				'bGuestHome' => ('Xs\Http\Controllers\IndexController' == $aParts[0] && 'getIndex' == $aParts[1]),
				'js' => $this->bHasJS
					? '<script>window.rxs_controller="' . $this->strController
						. '";window.rxs_action="' . $this->strAction . '";</script>'
					: ''
			]);
		}
	}

}
