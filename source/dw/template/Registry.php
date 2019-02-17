<?php declare(strict_types=1);

namespace dw\template;

/**
 * Simple registry containing values that need to be exposed to the template engine
 */
class Registry {

	/**
	 * Stylesheet sources to be loaded by the browser for this View.
	 * @var array
	 */
	public static $stylesheet = [];

	/**
	 * Javascript sources te be loaded by the browser for this View.
	 * @var array
	 */
	public static $javascript = [];

	/**
	 * Add a style to the registry
	 * @param  string $source stylesheet path
	 */
	public static function registerStyle( string $source ): void {
		self::$stylesheet[] = $source;
	}

	/**
	 * Add a scriptto the registry
	 * @param  string $source script path
	 */
	public static function registerScript( string $source ): void {
		self::$javascript[] = $source;
	}

	/**
	 * Get a unique list of all the stylesheets
	 * @return array
	 */
	public static function getStyles(): array {
		return array_unique( self::$stylesheets );
	}

	/**
	 * Get a unique list of all the scripts
	 * @return array
	 */
	public static function getScripts(): array {
		return array_unique( self::$javascript );
	}

}
?>