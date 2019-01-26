<?php declare(strict_types=1);
namespace dw;

/**
 *  Take care of findnig the appropiate dw\View.
 */
class Router {

	protected $routes = [];

	function __construct() {

	}

	/**
	 * Add a route to the internal registry
	 * @param string  $path     Path to be listening at
	 * @param string  $view     Subclass of dw\View
	 * @param integer $priority Where to be in the sorting list
	 */
	public function addRoute( string $path, string $view, int $priority = 10 ): Router {
		$this->routes[] = new Route(
			$path,
			$view,
			$priority
		);

		return $this;
	}

	/**
	 * Return a dw\Route for the path if possible
	 * @param  string $path Which path do we want to check for
	 * @return Route
	 */
	public function getRouteForPath( string $path ): ?Route {
		usort( $this->routes, function( Route $a, Route $b ) {
			return ( $a->getPriority() === $b->getPriority() )
					? 0
					: ( $a->getPriority() < $b->getPriority() )
						? -1
						: 1;
		} );

		foreach ( $this->routes as $route ) {
			if ( $route->isValidForPath( $path ) ) {
				return $route;
			}
		}

		return null;
	}

	public function create( Route $route ): View {
		$viewClass = "\\dw\\view\\" . $route->getView();
		return new $viewClass;
	}
}
?>