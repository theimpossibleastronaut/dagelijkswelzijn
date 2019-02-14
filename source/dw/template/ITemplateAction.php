<?php declare(strict_types=1);

namespace dw\template;

/**
 * Template actions are tags that can be used by the templating engine.
 * Typically a template action is named by its tag name and appending action to it.
 * A tag dw:example should thus be called ExampleAction and should live in the
 * namespace dw\template and thus becoming dw\template\ExampleAction
 */
interface ITemplateAction {

	/**
	 * Parse this action and return it's output as string
	 * @param  \DOMElement $node Input node
	 * @return string
	 */
	function parse( \DOMElement $node ): string;

	/**
	 * Parse an attribute
	 * @param  \string $attributeName
	 * @param  \string $attributeValue
	 * @return string
	 */
	function parseAttribute( string $attributeName, string $attributeValue ): string;
}
?>