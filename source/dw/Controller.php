<?php declare(strict_types=1);
namespace dw;

/**
 * Control the main application flow
 */
class Controller
{
	public static $templatePath = null;
	public static $storagePath = null;
	public static $cachePath = null;

	private $router = null;
	public function getRouter(): Router { return $this->router; }

	private $waf = null;
	public function getWAF(): WAF { return $this->waf; }

	function __construct() {
		self::$templatePath = realpath( '../template/' );
		self::$storagePath = realpath( '../storage/' );
		self::$cachePath = realpath( '../cache/' );

		$this->router = new Router;
		$this->waf = new WAF;

		Template\Registry::registerStyle( 'variables.css' );

		// If we suspect bad behaviour block all routing
		if ( $this->waf->getIsBlocked() ) {
			Template\Registry::registerStyle( 'blocked.css' );

			$this->router->addRoute( '/blocked', 'WAFView', 1 );
			$this->route( '/blocked' );
		} else {
			Template\Registry::registerStyle( 'app.css' );
			Template\Registry::registerScript( 'app.js' );


			$this->router->addRoute( '/', 'HomeView', 100 )
						 ->addRoute( '/login', 'LoginView' )
			;

			$this->route( $_SERVER[ 'REQUEST_URI' ] );
		}
	}

	/**
	 * Get the appropiate route, instantiate it and display it's contents on the screen
	 * @param  string $path Which path do we want to route
	 */
	public function route( string $path ) {
		$route = $this->router->getRouteForPath( $path );

		if ( is_null( $route ) ) {
			throw new \Exception( 'Missing route to handle \'' . $path . '\'' );
		}

		$view = $this->router->create( $route );
		$view->lazyLoad( true );
		$output = $view->render( false );

		echo $output;
	}
}
?>