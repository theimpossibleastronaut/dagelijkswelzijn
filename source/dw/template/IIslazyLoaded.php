<?php declare(strict_types=1);

namespace dw\template;

/**
 * Defers parsing until the last moment. This is implemented by Script and Stylesheet actions.
 * This allows us to wait and gather output.
 */
interface IIsLazyLoaded {
}
?>