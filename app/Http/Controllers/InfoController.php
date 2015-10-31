<?php

namespace Xs\Http\Controllers;

use Redirect;

class InfoController extends Controller
{

	/**
	 * Parent class overrides.
	 *
	 * @property bool $bHasJS Does this controller have an associated JS file?
	 */
	protected $bHasJS = false;

	/**
	 * About.
	 */
	public function getAbout()
	{
		return Redirect::to('/');
	}

	/**
	 * Help.
	 */
	public function getHelp()
	{
	}

}
