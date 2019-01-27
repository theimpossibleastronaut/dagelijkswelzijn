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

	private $router;
	public function getRouter(): Router { return $this->router; }

	function __construct() {
		self::$templatePath = realpath( '../template/' );
		self::$storagePath = realpath( '../storage/' );
		self::$cachePath = realpath( '../cache/' );

		$this->router = new Router();
		$this->router->addRoute( '/', 'HomeView', 100 )
					 ->addRoute( '/login', 'LoginView' )
		;

		$this->route( $_SERVER[ 'REQUEST_URI' ] );
	}

	/**
	 * Get the appropiate route, instantiate it and display it's contents on the screen
	 * @param  string $path Which path do we want to route
	 */
	public function route( string $path ) {
		$route = $this->router->getRouteForPath( $path );

		if ( is_null( $route ) ) {
			throw new Exception( 'Missing route to handle \'' . $path . '\'' );
		}

		$view = $this->router->create( $route );
		echo $view->render();
	}
}
?>