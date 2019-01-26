<?php declare(strict_types=1);
namespace dw;

/**
 * Class containing information about a route, like it's path and view class
 */
class Route
{
	protected $path = null;
	/** Get the path that we should listen on, could be / or /my/path */
	public function getPath(): string { return $this->path; }

	protected $view = null;
	/** Get the View subclass that we are */
	public function getView(): string { return $this->view; }

	protected $priority = 10;
	/** Get the priority on which we should be resolved */
	public function getPriority(): int { return $this->priority; }

	function __construct( $path, $view, $priority = 10 ) {

		if ( is_null( $path ) || empty( $path ) || !is_string( $path ) ) {
			throw new Exception( "Invalid path provided for Route" );
		}

		if ( is_null( $view ) || empty( $view ) || !is_string( $view ) ) {
			throw new Exception( "Invalid view provided for Route" );
		}

		if ( is_null( $priority ) || empty( $priority ) || !is_numeric( $priority ) ) {
			throw new Exception( "Invalid priority provided for Route" );
		}

		$this->path = trim( $path );
		$this->view = $view;
		$this->priority = $priority;
	}

	/**
	 * Is the current given route a valid View and applicable?
	 * @return bool Indicating if we should be used or not
	 */
	public function isValidForPath( $path ): bool {
		if ( trim( $path ) !== trim( $this->getPath() ) ) {
			return false;
		}

		$reflection = new \ReflectionClass( "\\dw\\View\\" . $this->getView() );
		if ( !$reflection->isSubclassOf( "dw\\View" ) ) {
			return false;
		}

		return true;
	}
}
?>