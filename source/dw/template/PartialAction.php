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
	 * @return string|array
	 */
	public function parse( \DOMElement $node ) {
		if ( $node->hasAttribute( 'src' ) ) {
			$src = (string) $node->getAttribute( 'src' );
			$template = new \dw\Template( $src );
			$output = $template->render( true, false );

			if ( $node->hasAttribute( 'stylesheet' ) ) {
				$attr = (string) $node->getAttribute( 'stylesheet' );
				Registry::registerStyle( empty( $attr ) ? $src : $attr );
			}

			if ( $node->hasAttribute( 'javascript' ) ) {
				$attr = (string) $node->getAttribute( 'javascript' );
				Registry::registerScript( empty( $attr ) ? $src : $attr );
			}

			return $output;
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