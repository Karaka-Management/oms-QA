<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\QA\Controller\ApiController;
use Modules\QA\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/qa/app(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAppCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::APP,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAppUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::APP,
            ],
        ],
    ],
    '^.*/qa/question(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAQuestionCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::QUESTION,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQuestionUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::QUESTION,
            ],
        ],
    ],
    '^.*/qa/question/vote(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiChangeQAQuestionVote',
            'verb'       => RouteVerb::PUT | RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VOTE,
            ],
        ],
    ],
    '^.*/qa/answer(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAnswerCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ANSWER,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiAnswerUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ANSWER,
            ],
        ],
    ],
    '^.*/qa/answer/vote(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiChangeQAAnswerVote',
            'verb'       => RouteVerb::PUT | RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::VOTE,
            ],
        ],
    ],
    '^.*/qa/answer/accept(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiChangeAnsweredStatus',
            'verb'       => RouteVerb::PUT | RouteVerb::SET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ACCEPT,
            ],
        ],
    ],
];
