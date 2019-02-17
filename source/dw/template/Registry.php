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

	public static function registerStyle( string $source ): void {
		self::$stylesheet[] = $source;
	}

	public static function registerScript( string $source ): void {
		self::$javascript[] = $source;
	}

}
?>