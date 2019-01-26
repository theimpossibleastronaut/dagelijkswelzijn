<?php declare(strict_types=1);
namespace dw;

class Controller
{
	private $router;
	public function getRouter(): Router { return $this->router; }

	function __construct() {
		$this->router = new Router();
		$this->router->addRoute( '/', 'HomeView', 100 )
					 ->addRoute( '/login', 'LoginView' )
		;

		$this->route( $_SERVER[ 'REQUEST_URI' ] );
	}

	public function route( $path = "/" ) {
		$route = $this->router->getRouteForPath( $path );

		if ( is_null( $route ) ) {
			throw new Exception( 'Missing route to handle \'' . $path . '\'' );
		}

		$view = $this->router->create( $route );
		echo $view->render();
	}
}
?>