<?php declare(strict_types=1);

namespace dw\template;

/**
 * Allows us to include other Template files by using the dw:partial tag
 */
class PartialAction implements ITemplateAction {

	function __construct() {}

	/**
	 * Parse this action and return it's output as string
	 * @param  \DOMElement $node Input node
	 * @return string
	 */
	public function parse( \DOMElement $node ): string {
		if ( $node->hasAttribute( 'src' ) ) {
			$attr = (string) $node->getAttribute( 'src' );
			$template = new \dw\Template( $attr );
			return $template->render( true, false );
		}

		return null;
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