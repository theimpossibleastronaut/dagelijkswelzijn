<?php declare(strict_types=1);

namespace dw\template;

/**
 * Print out all stylesheets
 */
class StylesheetAction implements ITemplateAction, IIsLazyLoaded {

	function __construct() {}

	/**
	 * Parse this action and return it's output as string
	 * @param  \DOMElement $node Input node
	 * @return string|array
	 */
	public function parse( \DOMElement $node ) {

		$output = [];

		foreach ( Registry::$stylesheet as $member ) {
			$output[] = "<link rel='stylesheet' href='/css/" . $member . "' />";
		}

		return $output;
	}

	/**
	 * Parse an attribute
	 * @param  \string $attributeName
	 * @param  \string $attributeValue
	 * @return string
	 */
	public function parseAttribute( string $attributeName, string $attributeValue ): string {
		return $attributeValue;
	}

}
?>