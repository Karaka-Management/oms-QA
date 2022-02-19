<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\Controller;

use phpOMS\Module\ModuleAbstract;

/**
 * QA controller class.
 *
 * @package Modules\QA
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Controller extends ModuleAbstract
{
    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__ . '/../';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    public const VERSION = '1.0.0';

    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    public const NAME = 'QA';

    /**
     * Module id.
     *
     * @var int
     * @since 1.0.0
     */
    public const ID = 1006000000;

    /**
     * Providing.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $providing = [];

    /**
     * Dependencies.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static array $dependencies = [];
}
