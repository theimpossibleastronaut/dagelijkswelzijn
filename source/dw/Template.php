<?php declare(strict_types=1);
namespace dw;

/**
 * Parse .html template files and make sure that all sub templates are adhered to properly
 */
class Template
{

	public $doc = null;

	function __construct( string $templateName ) {
		$templatePath = Controller::$templatePath . DIRECTORY_SEPARATOR . $templateName . ".html";

		if ( !file_exists( $templatePath ) ) {
			throw new \Exception( 'Template \'' . $templateName . '\' couldn\'t be found' );
		}

		$this->doc = new \DOMDocument( '1.0', 'utf-8' );
		$this->doc->loadXML(
			file_get_contents( $templatePath ),
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT | LIBXML_NOXMLDECL | LIBXML_PEDANTIC |
			LIBXML_NOERROR | LIBXML_NOWARNING /* Allow us to load prefixed tags without warning */
		);

		if ( is_null( $this->doc ) || $this->doc === false ) {
			throw new \Exception( 'Unable to parse document for \'' . $templateName . '\'' );
		}
	}

	/**
	 * Parse this template and try and find all of it's children to parse.
	 */
	public function parse(): void {
		$xp = new \DOMXPath( $this->doc );
		foreach( $xp->query('//*[starts-with(name(), "dw:")]') as $dwNode ) {
			if ( isset( $dwNode->parentNode ) ) {
				$nodeOutput = null;

				$className = "\\dw\\template\\" . ucfirst( substr( $dwNode->nodeName, 3) ) . "Action";

				if ( class_exists( $className ) ) {
					$action = new $className;
					$nodeOutput = $action->parse( $dwNode );
				}

				if ( !is_null( $nodeOutput ) ) {
					$lDoc = new \DOMDocument( '1.0', 'utf-8' );
					$lDoc->loadXML(
						$nodeOutput,
						LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT | LIBXML_NOXMLDECL | LIBXML_PEDANTIC |
						LIBXML_NOERROR | LIBXML_NOWARNING /* Allow us to load prefixed tags without warning */
					);

					$dwNode->parentNode->replaceChild( $this->doc->importNode( $lDoc->documentElement, true ), $dwNode );
				} else {
					$dwNode->parentNode->removeChild( $dwNode );
				}
			}
		}

		foreach ( $xp->query('//*[@*[contains(name(), "dw:")]]') as $dwNode ) {
			$attrPairs = [];
			foreach ( $dwNode->attributes as $k => $v ) {
				if ( substr( $k, 0, 3 ) === 'dw:' ) {
					$parts = explode( ':', $k );

					if ( count( $parts ) > 2 ) {
						$ns = array_shift( $parts );
						$action = array_shift( $parts );
						$attr = implode( ':', $parts );
						$content = $v->textContent;

						$attrOutput = null;

						$className = "\\dw\\template\\" . ucfirst( $action ) . "Action";

						if ( class_exists( $className ) ) {
							$action = new $className;
							$attrOutput = $action->parseAttribute( $attr, $content );
						}

						if ( !is_null( $attrOutput ) ) {
							// Store pair, because we shouldn't modify attributes while looping
							$attrPairs[ $k ] = [ $attr, $attrOutput ];
						}
					}
				}
			}

			foreach ( $attrPairs as $old => $new ) {
				$dwNode->removeAttribute( $old );
				$dwNode->setAttribute( $new[ 0 ], $new[ 1 ] );
			}
		}
	}

	/**
	 * Call the parser function and return the HTML output
	 * @return string
	 */
	public function render( bool $parse = true, bool $returnHTML = true ): string {
		if ( $parse === true ) {
			$this->parse();
		}

		return ( $returnHTML === true ) ? $this->doc->saveHTML() : $this->doc->saveXML();
	}
}
?>