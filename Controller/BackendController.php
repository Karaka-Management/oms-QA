<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Controller;

use Model\SettingMapper;
use Modules\Admin\Models\AccountMapper;
use Modules\Profile\Models\ProfileMapper;
use Modules\QA\Models\QAAppMapper;
use Modules\QA\Models\QAHelperMapper;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * QA backend controller class.
 *
 * @package Modules\QA
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setUpBackend(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $head = $response->data['Content']->head;
        $head->addAsset(AssetType::CSS, '/Modules/QA/Theme/Backend/styles.css?v=' . self::VERSION);
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQADashboard(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-dashboard');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        /** @var \Modules\QA\Models\QAQuestion[] $list */
        $view->data['questions'] = QAQuestionMapper::getAll()
            ->with('createdBy')
            ->with('createdBy/account')
            ->with('createdBy/image')
            ->with('votes')
            ->with('answers')
            ->with('answers/votes')
            ->with('tags')
            ->with('tags/title')
            ->where('tags/title/language', $response->header->l11n->language)
            ->where('language', $response->header->l11n->language)
            ->limit(50)->executeGetArray();


        $view->data['apps'] = QAAppMapper::getAll()
            ->where('unit', [$this->app->unitId, null])
            ->executeGetArray();

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQADoc(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        if (!$request->hasData('id')) {
            $response->header->status = RequestStatusCode::R_404;
            $view->setTemplate('/Web/Backend/Error/404');

            return $view;
        }

        $view->setTemplate('/Modules/QA/Theme/Backend/qa-question');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        /** @var \Modules\QA\Models\QAQuestion $question */
        $view->data['question'] = QAQuestionMapper::get()
            ->with('answers')
            ->with('answers/createdBy')
            ->with('answers/createdBy/image')
            ->with('answers/createdBy/account')
            ->with('answers/votes')
            ->with('createdBy')
            ->with('createdBy/account')
            ->with('createdBy/image')
            ->with('votes')
            ->with('tags')
            ->with('tags/title')
            ->with('files')
            ->where('id', (int) $request->getData('id'))
            ->where('tags/title/language', $response->header->l11n->language)
            ->execute();

        $view->data['scores'] = QAHelperMapper::getAccountScore($question->getAccounts());

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQAQuestionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-question-create');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        $question = new QAQuestion();

        $question->createdBy = ProfileMapper::get()
            ->with('account')
            ->with('image')
            ->where('account', $request->header->account)
            ->execute();

        if ($question->createdBy->account->id === 0) {
            $question->createdBy->account = AccountMapper::get()
                ->where('id', $request->header->account)
                ->execute();
        }

        $view->data['scores'] = QAHelperMapper::getAccountScore($question->getAccounts());

        $view->data['question'] = $question;

        return $view;
    }

    /**
     * Method which generates the module settings view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     */
    public function viewModuleSettings(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view              = new View($this->app->l11nManager, $request, $response);
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000105001, $request, $response);

        $id = $request->getDataString('id') ?? '';

        /** @var \Model\Setting[] $settings */
        $view->data['settings'] = SettingMapper::getAll()
            ->where('module', $id)
            ->executeGetArray();

        $view->data['apps'] = QAAppMapper::getAll()
            ->where('unit', [$this->app->unitId, null])
            ->executeGetArray();

        $view->setTemplate('/Modules/' . static::NAME . '/Admin/Settings/Theme/Backend/settings');

        return $view;
    }

    /**
     * Method which generates a app settings view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     */
    public function viewAppSettings(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/' . static::NAME . '/Admin/Settings/Theme/Backend/settings-app');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1000105001, $request, $response);

        $view->data['app'] = QAAppMapper::get()
            ->where('id', (int) $request->getData('app'))
            ->execute();

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQAAppCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-app-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQAAppList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/QA/Theme/Backend/qa-app-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        $view->data['apps'] = QAAppMapper::getAll()
            ->where('unit', [$this->app->unitId, null])
            ->executeGetArray();

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQAApp(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/QA/Theme/Backend/qa-app-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response);

        $view->data['app'] = QAAppMapper::get()
            ->where('id', (int) $request->getData('id'))
            ->execute();

        return $view;
    }
}
