<?php

namespace Xs\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
    	// Sometimes 404s throw zero instead
    	$iCode = $e->getCode() ?: 404;

    	// AJAX/command line
    	if (\Request::ajax() || \App::runningInConsole()) {
    		$aResponse = [
    			'error' => [
    				'message' => $e->getMessage(),
    				'code' => $iCode,
    			]
    		];
    		if (!\App::environment('production'))
    			$aResponse['error']['trace'] = $e->getTrace();

    		return response()->json($aResponse, $iCode);

    	// HTML
    	} else {
	        return response()->view('errors.index', ['exception' => $e], $iCode);
    	}
    }
}
