<?php declare(strict_types=1);

/**
 * Create basic POT template path
 * and extract dw:translate tags from it.
 */

namespace locale;

define( 'POTPATH', 'locale/messages.pot' );
define( 'TEMPLATEPATH', realpath( 'template' ) );

class POT {

	protected $output = '# Messages file for Dagelijks Welzijn.
# This file is licensed under the same license as the main application.
# FIRST AUTHOR <EMAIL@ADDRESS>, YEAR.
#
#,fuzzy
msgid ""
msgstr ""
"Report-Msgid-Bugs-To: translations@sexybiggetje.nl\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\n"
"Last-Translator: FULL NAME <EMAIL@ADDRESS>\n"
"Language-Team: LANGUAGE <LL@li.org>\n"
"Language: \n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"' . PHP_EOL . PHP_EOL;

	function __construct() {

		$it = new \GlobIterator( TEMPLATEPATH . '/*.html' );

		foreach ( $it as $fi ) {
			$this->parseFile( $fi );
		}

		file_put_contents( POTPATH, $this->output );
	}

	protected function parseFile( \SplFileInfo $fi ): void {

		$doc = new \DOMDocument( '1.0', 'utf-8' );
		$doc->loadXML(
			file_get_contents( $fi->getPathname() ),
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOENT | LIBXML_NOXMLDECL | LIBXML_PEDANTIC |
			LIBXML_NOERROR | LIBXML_NOWARNING /* Allow us to load prefixed tags without warning */
		);

		if ( is_null( $doc ) || $doc === false ) {
			throw new \Exception( 'Unable to parse document for \'' . $fi->fileName . '\'' );
		}

		foreach ( $doc->getElementsByTagName( 'dw:translate' ) as $node ) {
			$this->processNode( $node, $fi );
		}

		$xp = new \DOMXPath( $doc );
		foreach ( $xp->query('//*[@*[contains(name(), "dw:")]]') as $node ) {
			foreach ( $node->attributes as $k => $v ) {
				if ( substr( $k, 0, 3 ) === 'dw:' ) {
					$parts = explode( ':', $k );

					if ( count( $parts ) > 2 ) {
						$this->processAttribute( $node, $fi, $k, $v->textContent );
					}
				}
			}
		}

	}

	protected function processNode( \DOMNode $node, \SplFileInfo $fi ): void {

		$this->output .= '#: ' . $fi->getFilename() . ':' . $node->getLineNo() . PHP_EOL;
		$this->output .= 'msgid "' . $this->escape( $node->nodeValue ) . '"' . PHP_EOL;
		$this->output .= 'msgstr ""' . PHP_EOL . PHP_EOL;

	}

	protected function processAttribute( \DOMNode $node, \SplFileInfo $fi, string $attribute, string $value ): void {

		$this->output .= '#: ' . $fi->getFilename() . ':' . $node->getLineNo() . "@" . $attribute . PHP_EOL;
		$this->output .= 'msgid "' . $this->escape( $value ) . '"' . PHP_EOL;
		$this->output .= 'msgstr ""' . PHP_EOL . PHP_EOL;

	}

	protected function escape( string $value ): string {
		return addslashes( $value );
	}

}

new POT;
?>