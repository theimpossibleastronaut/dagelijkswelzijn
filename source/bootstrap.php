<?php declare(strict_types=1);

	spl_autoload_register( function( string $className ) {
		$path = realpath( dirname( __FILE__ ) . "/" . str_replace( '\\', DIRECTORY_SEPARATOR, $className . '.php' ) );

		if ( is_string( $path ) && file_exists( $path ) ) {
			require_once( $path );
			return true;
		} else {
			throw new \Exception( "Unable to load requested class '" . $className . "' by looking at '" . $path . "'" );
		}

		return false;
	} );
?>