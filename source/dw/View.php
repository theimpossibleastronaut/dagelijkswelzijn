<?php declare(strict_types=1);
namespace dw;

class View
{
	function __construct() {

	}

	public function render(): string {
		return get_called_class();
	}
}
?>