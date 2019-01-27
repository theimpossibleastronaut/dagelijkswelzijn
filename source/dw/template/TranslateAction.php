<?php declare(strict_types=1);

namespace dw\template;

/**
 * Allows us to translate a text to the current locale.
 * Note that this shouldn't contain markup as we work on text values only
 *
 * dw:translate is by default transformed into span and it's attributes are copied over
 * you can change this wrapper element to anything by specifying its tag into nodeType
 */
class TranslateAction implements ITemplateAction {

	function __construct() {}

	/**
	 * Parse this action and return it's output as string
	 * @param  \DOMElement $node Input node
	 * @return string
	 */
	public function parse( \DOMElement $node ): string {

		// @TODO implement gettext everywhere
		$translatedString = implode(
			array_map(
				[ $node->ownerDocument, 'saveXML' ],
				iterator_to_array( $node->childNodes )
			)
		);

		$nodeType = 'span';

		// Allow changing the wrapper element that is returned
		if ( $node->hasAttribute( 'nodeType' ) ) {
			$nodeType = (string) $node->getAttribute( 'nodeType' );
		}

		$outnode = "<" . $nodeType;

		foreach ( $node->attributes as $attr ) {
			if ( $attr->nodeName !== 'nodeType' ) {
				$outnode .= ' ' . $attr->nodeName . '="' . $attr->nodeValue . '"';
			}
		}

		$outnode .= '>' . $translatedString . '</' . $nodeType . '>';

		return $outnode;
	}

}
?>