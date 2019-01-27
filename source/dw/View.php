<?php declare(strict_types=1);
namespace dw;

class View
{
	protected $template = null;

	function __construct() {
		$this->template = new Template( 'base' );
	}

	public function render(): string {
		return $this->template->render();
	}
}
?>