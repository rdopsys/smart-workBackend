<?php
/**
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace TotalPollVendors\Interop\Container\Exception;
! defined( 'ABSPATH' ) && exit();


use TotalPollVendors\Psr\Container\ContainerExceptionInterface as PsrContainerException;

/**
 * Base interface representing a generic exception in a container.
 */
interface ContainerException extends PsrContainerException
{
}
