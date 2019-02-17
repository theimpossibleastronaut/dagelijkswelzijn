<?php declare(strict_types=1);
namespace dw;

class View
{
	protected $template = null;
	protected $baseTemplate = 'base';

	function __construct() {
		$this->template = new Template( $this->baseTemplate );
	}

	public function render( bool $parse = true, bool $returnHTML = true ): string {
		return $this->template->render( $parse, $returnHTML );
	}

	public function lazyLoad( bool $parse = true ): void {
		$this->template->lazyLoad( $parse );
	}
}
?>