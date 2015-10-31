<?php

namespace Xs\Http\Controllers;

class TermsController extends Controller
{

	/**
	 * Parent class overrides.
	 *
	 * @property bool $bHasJS Does this controller have an associated JS file?
	 */
	protected $bHasJS = false;

	/**
	 * Terms of Use.
	 */
	public function getIndex()
	{
	}

	/**
	 * Privacy Policy.
	 */
	public function getPrivacy()
	{
	}

}
