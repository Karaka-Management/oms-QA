<?php declare(strict_types=1);

use Modules\QA\Controller\ApiController;
use Modules\QA\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/qa/category(\?.*|$)' => [
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQACategoryCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::CATEGORY,
            ],
        ],
        [
            'dest'       => '\Modules\QA\Controller\ApiController:apiQACategoryUpdate',
            'verb'       => RouteVerb::SET,
            'permission' => [
                'module' => ApiController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionState::CATEGORY,
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
