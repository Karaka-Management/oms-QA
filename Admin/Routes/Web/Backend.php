<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use Modules\QA\Controller\BackendController;
use Modules\QA\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/admin/module/settings\?id=QA$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:viewModuleSettings',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => \Modules\Admin\Models\PermissionCategory::MODULE,
            ],
        ],
    ],
    '^.*/admin/module/settings\?id=QA&app=.*?$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:viewAppSettings',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => \Modules\Admin\Models\PermissionCategory::MODULE,
            ],
        ],
    ],
    '^.*/qa.*$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:setUpBackend',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::QA,
            ],
        ],
    ],
    '^.*/qa/dashboard.*$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:viewQADashboard',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::QA,
            ],
        ],
    ],
    '^.*/qa/question.*$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:viewQADoc',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::QUESTION,
            ],
        ],
    ],
    '^.*/qa/question/create.*$' => [
        [
            'dest'       => '\Modules\QA\Controller\BackendController:viewQAQuestionCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::QUESTION,
            ],
        ],
    ],
];
