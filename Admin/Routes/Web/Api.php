<?php declare(strict_types=1);

use Modules\QA\Controller\ApiController;
use Modules\QA\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/qa/app(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAppCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::APP,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAppUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::APP,
            ],
        ],
    ],
    '^.*/qa/question(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAQuestionCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::QUESTION,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQuestionUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::QUESTION,
            ],
        ],
    ],
    '^.*/qa/question/vote(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiChangeQAQuestionVote',
            'verb'       => RouteVerb::PUT | RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::VOTE,
            ],
        ],
    ],
    '^.*/qa/answer(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQAAnswerCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::ANSWER,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiAnswerUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::ANSWER,
            ],
        ],
    ],
    '^.*/qa/answer/vote(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiChangeQAAnswerVote',
            'verb'       => RouteVerb::PUT | RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::VOTE,
            ],
        ],
    ],
];
