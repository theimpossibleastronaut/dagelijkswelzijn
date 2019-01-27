<?php declare(strict_types=1);

namespace dw;

/**
 * Try and stop bad behaviour from entering our application logic
 *
 * Code and methods are heavily inspired by wp-waf
 * @see  https://github.com/guelfoweb/wp-waf/
 */
class WAF
{
	private $isBlocked = false;
	public function getIsBlocked(): bool { return $this->isBlocked; }

	/**
	 * Block some keywords
	 */
	const XSS_FILTER = "javascript|vbscript|expression|applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|form|img|body|href|div|cdata";

	/**
	 * Block some user agents by default we don't want crawlers like these anyway
	 */
	const USER_AGENT_FILTER = "curl|wget|winhttp|HTTrack|clshttp|loader|email|harvest|extract|grab|miner|libwww-perl|acunetix|sqlmap|python|nikto|scan";

	/**
	 * Some SQL statements
	 */
	const SQL_FILTER = "[\x22\x27](\s)*(or|and)(\s).*(\s)*\x3d|cmd=ls|cmd%3Dls|(drop|alter|create|truncate).*(index|table|database)|insert(\s).*(into|member.|value.)|(select|union|order).*(select|union|order)|0x[0-9a-f][0-9a-f]|benchmark\([0-9]+,[a-z]+|benchmark\%28+[0-9]+%2c[a-z]+|eval\(.*\(.*|eval%28.*%28.*|update.*set.*=|delete.*from";

	/**
	 * Path traversal
	 */
	const TRAVERSAL_FILTER = "\.\.\/|\.\.\\|%2e%2e%2f|%2e%2e\/|\.\.%2f|%2e%2e%5c";

	/**
	 * Some filters for remote file inclusion
	 */
	const REMOTE_FILE_FILTER = "%00|(?:((?:ht|f)tp(?:s?)|file|webdav)\:\/\/|~\/|\/).*\.\w{2,3}|(?:((?:ht|f)tp(?:s?)|file|webdav)%3a%2f%2f|%7e%2f%2f).*\.\w{2,3}";

	/**
	 * Block some request methods
	 */
	const REQUEST_METHOD_FILTER = "TRACE|DELETE|TRACK";

	// Blacklist rules
	const XSS_REFERER_RULE = "XSS REFERER";
	const XSS_QUERY_STRING_RULE = "XSS QUERY STRING";
	const USER_AGENT_EMPTY_RULE = "USER AGENT EMPTY";
	const USER_AGENT_RULE = "USER AGENT";
	const SQL_RULE = "SQL";
	const TRAVERSAL_RULE = "TRAVERSAL";
	const REMOTE_FILE_RULE = "REMOTE FILE";
	const REQUEST_METHOD_RULE = "REQUEST METHOD";
	const QUERY_STRING_TOOLONG_RULE = "QUERY STRING TOO LONG";

	function __construct() {
		$scanDetails = $this->scanRequest();

		$this->isBlocked = ( count( $scanDetails ) > 0 );
	}

	/**
	 * Scan the current request for bad info
	 * @return array Matched rules
	 */
	protected function scanRequest(): array {
		$ruleMatches = [];

		$matches = [];

		$reqMethod = "";
		if ( isset( $_SERVER[ "REQUEST_METHOD" ] ) ) {
			$reqMethod = $_SERVER[ "REQUEST_METHOD" ];
		}

		$referer = "";
		if ( isset( $_SERVER[ "HTTP_REFERER" ] ) ) {
			$referer = $_SERVER[ "HTTP_REFERER" ];
		}

		$userAgent = "";
		if ( isset( $_SERVER[ "HTTP_USER_AGENT" ] ) ) {
			$userAgent = $_SERVER[ "HTTP_USER_AGENT" ];
		}

		$queryString = "";
		if ( isset( $_SERVER[ "QUERY_STRING" ] ) ) {
			$queryString = $_SERVER[ "QUERY_STRING" ];
		}

		if ( preg_match( "/^(" . self::REQUEST_METHOD_FILTER . ")/i", $reqMethod, $matches ) ) {
			$ruleMatches[] = self::REQUEST_METHOD_RULE;
		}

		if ( preg_match( "/<[^>]*(" . self::XSS_FILTER . ")[^>]*>/i", $referer, $matches ) ) {
			$ruleMatches[] = self::XSS_REFERER_RULE;
		}

		if ( preg_match( "/(^$)/i", $userAgent, $matches ) ) {
			$ruleMatches[] = self::USER_AGENT_EMPTY_RULE;
		}

		if ( preg_match( "/^(" . self::USER_AGENT_FILTER . ")/i", $userAgent, $matches ) ) {
			$ruleMatches[] = self::USER_AGENT_RULE;
		}

		if ( preg_match( "/(<|<.)[^>]*(" . self::XSS_FILTER . ")[^>]*>/i", $queryString, $matches ) ) {
			$ruleMatches[] = self::XSS_QUERY_STRING_RULE;
		}

		if ( preg_match( "/((\%3c)|(\%3c).)[^(\%3e)]*(" . self::XSS_FILTER . ")[^(\%3e)]*(%3e)/i", $queryString, $matches ) ) {
			$ruleMatches[] = self::XSS_QUERY_STRING_RULE;
		}

		if ( preg_match( "/^.*(" . self::TRAVERSAL_FILTER . ").*/i", $queryString, $matches ) ) {
			$ruleMatches[] = self::TRAVERSAL_RULE;
		}

		if ( preg_match( "/^.*(" . self::REMOTE_FILE_FILTER . ").*/i", $queryString, $matches ) ) {
			$ruleMatches[] = self::REMOTE_FILE_RULE;
		}

		if ( preg_match( "/^.*(" . self::SQL_FILTER . ").*/i", $queryString, $matches ) ) {
			$ruleMatches[] = self::SQL_RULE;
		}

		if ( strlen( $_SERVER[ "QUERY_STRING" ] ) > 255 ) {
			$ruleMatches[] = self::QUERY_STRING_TOOLONG_RULE;
		}

		return $ruleMatches;
	}
}
?>