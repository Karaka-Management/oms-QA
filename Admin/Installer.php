<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\QA\Admin;

use Modules\QA\Models\QAApp;
use Modules\QA\Models\QAAppMapper;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * Installer class.
 *
 * @package Modules\QA\Admin
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(DatabasePool $dbPool, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($dbPool, $info, $cfgHandler);

        $app       = new QAApp();
        $app->name = 'Backend';

        $id = QAAppMapper::create()->execute($app);
    }
}
