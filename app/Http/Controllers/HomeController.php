<?php

namespace Xs\Http\Controllers;

use App, Auth, Disciplefy, Input, Redirect, Request, Response, View;

class HomeController extends Controller
{

	/**
	 * Require login.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * User homepage.
	 */
	public function getIndex()
	{
	}

}

