<?php declare(strict_types=1);

use phpOMS\Router\RouteVerb;

return [
    '^(\/[a-zA-Z]*\/*|\/)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewList',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^(\/[a-zA-Z]*\/*|\/)/profile(\?.*|$)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewProfile',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^(\/[a-zA-Z]*\/*|\/)/imprint(\?.*|$)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewImprint',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^(\/[a-zA-Z]*\/*|\/)/terms(\?.*|$)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewTerms',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^(\/[a-zA-Z]*\/*|\/)/privacy(\?.*|$)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewDataPrivacy',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^(\/[a-zA-Z]*\/*|\/)/question(\?.*|$)$' => [
        [
            'dest' => '\Web\{APPNAME}\Controller\AppController:viewQuestion',
            'verb' => RouteVerb::GET,
        ],
    ],
];
