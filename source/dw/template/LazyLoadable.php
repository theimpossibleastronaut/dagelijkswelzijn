<?php declare(strict_types=1);

namespace dw\template;

/**
 * Data structure for deferred loadables
 */
class LazyLoadable {
	/**
	 * Hash
	 * @var string
	 */
	public $hash;

	/**
	 * DOMNode
	 * @var DOMNode
	 */
	public $node;

	/**
	 * Action to execute
	 * @var ITemplateAction
	 */
	public $action;

	/**
	 * DOMDocument
	 * @var DOMDocument
	 */
	public $doc;
}
?>