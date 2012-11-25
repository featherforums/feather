<?php namespace Feather\Presenter\Compilers;

use Illuminate\View\Compilers\BladeCompiler;

class FeatherCompiler extends BladeCompiler {

	/**
	 * Compile the given template contents.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function compileString($value)
	{
		$this->compilers = array_merge($this->compilers, array(
			'Assignments',
			'ExtensionEvents',
			'InlineErrors',
			'Errors'
		));

		return parent::compileString($value);
	}

	/**
	 * Compile assignments into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileAssignments($value)
	{
		return preg_replace('/(\s*)@assign\s*\(\$(.*), (.*)\)(\s*)/', '$1<?php $$2 = $3; ?>$4', $value);
	}

	/**
	 * Compile extension events into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileExtensionEvents($value)
	{
		$pattern = $this->createMatcher('event');

		return preg_replace($pattern, '$1<?php echo Feather\Extension::fire$2; ?>', $value);
	}

	/**
	 * Compile inline errors into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileInlineErrors($value)
	{
		$pattern = $this->createMatcher('error');

		return preg_replace($pattern, '$1<?php echo $errors->has$2 ? view("feather::errors.inline", array("error" => $errors->first$2)) : null; ?>', $value);
	}

	/**
	 * Compile errors into valid PHP.
	 * 
	 * @param  string  $value
	 * @return string
	 */
	public function compileErrors($value)
	{
		return str_replace('@errors', '<?php echo $errors->all() ? view("feather::errors.page", array("errors" => $errors->all())) : null; ?>', $value);
	}

}