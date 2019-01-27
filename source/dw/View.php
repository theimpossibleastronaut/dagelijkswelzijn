<?php declare(strict_types=1);
namespace dw;

class View
{
	protected $template = null;
	protected $baseTemplate = 'base';

	function __construct() {
		$this->template = new Template( $this->baseTemplate );
	}

	public function render(): string {
		return $this->template->render();
	}
}
?>