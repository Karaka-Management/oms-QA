<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\Controller;

use Model\NullSetting;
use Model\SettingMapper;
use Modules\QA\Models\QAAppMapper;
use Modules\QA\Models\QAHelperMapper;
use Modules\QA\Models\QAQuestionMapper;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * QA backend controller class.
 *
 * @package Modules\QA
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setUpBackend(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $head = $response->get('Content')->getData('head');
        $head->addAsset(AssetType::CSS, '/Modules/QA/Theme/Backend/styles.css');
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQADashboard(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-dashboard');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response));

        $list = QAQuestionMapper::getAll()
            ->with('createdBy')
            ->with('createdBy/account')
            ->with('votes')
            ->with('answers')
            ->with('answers/votes')
            ->with('tags')
            ->with('tags/title')
            ->where('tags/title/language', $response->getLanguage())
            ->where('language', $response->getLanguage())
            ->limit(50)->execute();

        $view->setData('questions', $list);

        $apps = QAAppMapper::getAll()->execute();
        $view->setData('apps', $apps);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQADoc(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-question');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response));

        $question = QAQuestionMapper::get()
            ->with('answers')
            ->with('answers/createdBy')
            ->with('answers/createdBy/account')
            ->with('answers/votes')
            ->with('createdBy')
            ->with('createdBy/account')
            ->with('votes')
            ->with('tags')
            ->with('tags/title')
            ->with('media')
            ->where('id', (int) $request->getData('id'))
            ->where('tags/title/language', $response->getLanguage())
            ->execute();

        $view->addData('question', $question);

        $scores = QAHelperMapper::getAccountScore($question->getAccounts());
        $view->addData('scores', $scores);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewQAQuestionCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/QA/Theme/Backend/qa-question-create');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1006001001, $request, $response));

        $question = QAQuestionMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $view->addData('question', $question);

        return $view;
    }

    /**
     * Method which generates the module settings view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     */
    public function viewModuleSettings(RequestAbstract $request, ResponseAbstract $response, $data = null): RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000105001, $request, $response));

        $id = $request->getData('id') ?? '';

        $settings = SettingMapper::getAll()->where('module', $id)->execute();
        if (!($settings instanceof NullSetting)) {
            $view->setData('settings', !\is_array($settings) ? [$settings] : $settings);
        }

        $apps = QAAppMapper::getAll()->execute();
        $view->setData('apps', $apps);

        if (\is_file(__DIR__ . '/../Admin/Settings/Theme/Backend/settings.tpl.php')) {
            $view->setTemplate('/Modules/' . static::NAME . '/Admin/Settings/Theme/Backend/settings');
        } else {
            $view->setTemplate('/Modules/Admin/Theme/Backend/modules-settings');
        }

        return $view;
    }

    /**
     * Method which generates a app settings view.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface Response can be rendered
     *
     * @since 1.0.0
     */
    public function viewAppSettings(RequestAbstract $request, ResponseAbstract $response, $data = null): RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/' . static::NAME . '/Admin/Settings/Theme/Backend/settings-app');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1000105001, $request, $response));

        $view->addData('app', QAAppMapper::get()->where('id', (int) $request->getData('app'))->execute());

        return $view;
    }
}
