<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA\Admin\Install
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Admin\Install;

use phpOMS\Application\ApplicationAbstract;

/**
 * Navigation class.
 *
 * @package Modules\QA\Admin\Install
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Navigation
{
    /**
     * Install navigation providing
     *
     * @param ApplicationAbstract $app  Application
     * @param string              $path Module path
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function install(ApplicationAbstract $app, string $path) : void
    {
        \Modules\Navigation\Admin\Installer::installExternal($app, ['path' => __DIR__ . '/Navigation.install.json']);
    }
}
