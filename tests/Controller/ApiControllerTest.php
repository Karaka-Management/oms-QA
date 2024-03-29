<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use Modules\QA\Models\QAAnswerStatus;
use Modules\QA\Models\QAQuestionStatus;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\L11nManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\System\MimeType;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\QA\Controller\ApiController::class)]
#[\PHPUnit\Framework\Attributes\TestDox('Modules\QA\tests\Controller\ApiControllerTest: QA api controller')]
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\QA\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId         = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');
        $this->app->sessionManager = new HttpSession(36000);
        $this->app->l11nManager    = new L11nManager();

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission       = new AccountPermission();
        $permission->unit = $this->app->unitId;
        $permission->app  = $this->app->appId;
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('QA');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAAppCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('name', 'TestQAApp');

        $this->module->apiQAAppCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAAppCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiQAAppCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAQuestionCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('title', 'Test Question');
        $request->setData('plain', 'Question content');
        $request->setData('language', ISO639x1Enum::_EN);
        $request->setData('status', QAQuestionStatus::ACTIVE);
        $request->setData('tags', '[{"title": "TestTitle", "color": "#f0f", "language": "en"}, {"id": 1}]');

        if (!\is_file(__DIR__ . '/test_tmp.md')) {
            \copy(__DIR__ . '/test.md', __DIR__ . '/test_tmp.md');
        }

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => 'test.md',
                'type'     => MimeType::M_TXT,
                'tmp_name' => __DIR__ . '/test_tmp.md',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/test_tmp.md'),
            ],
        ]);

        $request->setData('media', \json_encode([1]));

        $this->module->apiQAQuestionCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAQuestionCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiQAQuestionCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAAnswerCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('question', '1');
        $request->setData('plain', 'Answer content');
        $request->setData('status', QAAnswerStatus::ACTIVE);

        if (!\is_file(__DIR__ . '/test_tmp.md')) {
            \copy(__DIR__ . '/test.md', __DIR__ . '/test_tmp.md');
        }

        TestUtils::setMember($request, 'files', [
            'file1' => [
                'name'     => 'test.md',
                'type'     => MimeType::M_TXT,
                'tmp_name' => __DIR__ . '/test_tmp.md',
                'error'    => \UPLOAD_ERR_OK,
                'size'     => \filesize(__DIR__ . '/test_tmp.md'),
            ],
        ]);

        $request->setData('media', \json_encode([1]));

        $this->module->apiQAAnswerCreate($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiChangeAnsweredStatus() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('id', '1');
        $request->setData('accepted', '1');

        $this->module->apiChangeAnsweredStatus($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiQAAnswerCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiQAAnswerCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiChangeQAQuestionVote() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('id', '1');
        $request->setData('type', '-1');

        $this->module->apiChangeQAQuestionVote($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);

        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('id', '1');
        $request->setData('type', '1');

        $this->module->apiChangeQAQuestionVote($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiChangeQAQuestionVoteInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiChangeQAQuestionVote($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiChangeQAAnswerVote() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 2;
        $request->setData('id', '1');
        $request->setData('type', '-1');

        $this->module->apiChangeQAAnswerVote($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);

        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 2;
        $request->setData('id', '1');
        $request->setData('type', '1');

        $this->module->apiChangeQAAnswerVote($request, $response);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testApiChangeQAAnswerVoteInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest();

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiChangeQAAnswerVote($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }
}
