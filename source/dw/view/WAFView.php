<?php declare(strict_types=1);
namespace dw\view;

/**
 * Template shown whenever someone hits the WAF
 */
class WAFView extends \dw\View {
	protected $baseTemplate = 'waf';

	function __construct() {
		parent::__construct();

		if ( headers_sent() ) {
			header("HTTP/1.1 401 Unauthorized");
		}
	}
}
?>