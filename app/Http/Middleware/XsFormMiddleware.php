<?php

namespace Xs\Http\Middleware;

use App, Auth, Closure, Form, Honeypot;

class XsFormMiddleware
{
    /**
     * Add form macros.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		Form::macro('xsOpen', function($options) {
			// role
			if (!isset($options['role']))
				$options['role'] = 'form';
			// id
			if (!isset($options['id'])) {
				// Go back up the trace and find the calling view partial.
				// NOTE: Limit 1 <form> per view file.
				foreach (debug_backtrace() as $aTrace) {
					if (isset($aTrace['object']) && 'Illuminate\View\View' === get_class($aTrace['object'])) {
						$options['id'] = str_replace('.', '-', $aTrace['object']->getName());
						break;
					}
				}
			}

			return Form::open($options);
		});
		Form::macro('xsEmail', function($name, $value = null, $options = []){
			return Form::email($name, $value, XsFormMiddleware::addDefaultFieldOptions($options));
		});
		Form::macro('xsPassword', function($name, $options = []){
			return Form::password($name, XsFormMiddleware::addDefaultFieldOptions($options));
		});
		Form::macro('xsCheckbox', function($name, $value = 1, $checked = null, $options = [], $strInlineLabel = ''){
			if ($strInlineLabel)
				$strInlineLabel = ' ' . $strInlineLabel;
			return '<label class="checkbox-inline">' . Form::checkbox($name, $value, $checked, $options) . $strInlineLabel . '</label>';
		});
		Form::macro('xsRadio', function($name, $value = null, $checked = null, $options = [], $strInlineLabel = ''){
			if ($strInlineLabel)
				$strInlineLabel = ' ' . $strInlineLabel;
			return '<label class="radio-inline">' . Form::radio($name, $value, $checked, $options) . $strInlineLabel . '</label>';
		});
		Form::macro('xsSelect', function($name, $list = [], $selected = null, $options = [], $bSkipDefaults = false){
			if (isset($options['readonly']) && true == $options['readonly']) {
				echo Form::hidden($name, $selected);
				$options['disabled'] = true;
			}
			return Form::select(
				$name,
				$list,
				$selected,
				$bSkipDefaults ? $options : XsFormMiddleware::addDefaultFieldOptions($options)
			);
		});
		Form::macro('xsText', function($name, $value = null, $options = []){
			return Form::text($name, $value, XsFormMiddleware::addDefaultFieldOptions($options));
		});
		Form::macro('xsTextarea', function($name, $value = null, $options = []){
			return Form::textarea($name, $value, XsFormMiddleware::addDefaultFieldOptions($options));
		});
		Form::macro('editable', function($strName, $strType, $mValue, $strEmptyText = '', $aSource = [], $iPk = null){
			$aProps = [
				'class' => 'editable',
				'data-url' => '/profile/edit',
				'data-name' => $strName,
				'data-type' => $strType,
				'data-token' => csrf_token(),
				'data-pk' => $iPk ? $iPk : Auth::id(),
				'data-emptytext' => $strEmptyText,
				'href' => '#',
			];
			if ('text' === $strType) {
				if (empty($strEmptyText))
					App::abort(500, __METHOD__ . ': $strEmptyText must not be empty.');
			} elseif ('select' === $strType) {
				if (empty($aSource))
					App::abort(500, __METHOD__ . ': $aSource must not be empty.');
				$aProps['data-value'] = $mValue;
				$aProps['data-source'] = json_encode($aSource);
			}

			$strProps = '';
			foreach ($aProps as $strPropName => $strPropValue) {
				$strQuoteValue = ('data-source' === $strPropName)
					? '\'' . $strPropValue . '\''
					: '"' . $strPropValue . '"';
				$strProps .= ' ' . $strPropName . '=' . $strQuoteValue;
			}

			return '<a' . $strProps . '>' . $mValue . '</a>';
		});
		Form::macro('honeypot', function($name, $time){
			return Honeypot::generate($name, $time);
		});
		Form::macro('xsSubmit', function($value = null, $options = []){
			return Form::submit($value, XsFormMiddleware::addDefaultSubmitOptions($options));
		});

        return $next($request);
    }

	/**
	 * Add default options to form elements, namely Twitter Bootstrap classes.
	 *
	 * @param array $aOptions
	 * @return array
	 */
	public static function addDefaultFieldOptions($aOptions)
	{
		$aDefaultOptions = ['class' => 'form-control'];
		if (isset($aOptions['class']))
			$aDefaultOptions['class'] .= ' ' . $aOptions['class'];
		return array_merge($aOptions, $aDefaultOptions);
	}

	/**
	 * Add default options to submit buttons, namely Twitter Bootstrap classes.
	 *
	 * You can remove the "btn-block" class by passing $aOptions['no-block'] = true
	 *
	 * @param array $aOptions
	 * @return array
	 */
	public static function addDefaultSubmitOptions($aOptions)
	{
		$bBlock = true;
		if (isset($aOptions['no-block'])) {
			$bBlock = false;
			unset($aOptions['no-block']);
		}
		$aDefaultOptions = ['class' => 'btn btn-info' . ($bBlock ? ' btn-block' : '')];
		if (isset($aOptions['class']))
			$aDefaultOptions['class'] .= ' ' . $aOptions['class'];
		return array_merge($aOptions, $aDefaultOptions);
	}

}
