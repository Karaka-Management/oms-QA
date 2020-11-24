<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\QA\Controller;

use Modules\QA\Models\NullQACategory;
use Modules\QA\Models\NullQAQuestion;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerMapper;
use Modules\QA\Models\QAAnswerStatus;
use Modules\QA\Models\QACategory;
use Modules\QA\Models\QACategoryMapper;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionMapper;
use Modules\QA\Models\QAQuestionStatus;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Utils\Parser\Markdown\Markdown;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\NotificationLevel;
use Modules\QA\Models\QACategoryL11n;
use Modules\Tag\Models\NullTag;
use phpOMS\Message\Http\HttpResponse;
use Modules\Admin\Models\NullAccount;
use Modules\QA\Models\QACategoryL11nMapper;

/**
 * Task class.
 *
 * @package Modules\QA
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/Modules#70
 *  There is no voting implemented right now, this needs to be added (visually and model/database)
 *
 * @todo Orange-Management/Modules#78
 *  Edit functionality
 *  Currently nothing can be edited (change)
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a question
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAQuestionCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateQAQuestionCreate($request))) {
            $response->set('qa_question_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $question = $this->createQAQuestionFromRequest($request, $response, $data);
        $this->createModel($request->header->account, $question, QAQuestionMapper::class, 'question', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Question', 'Question successfully created.', $question);
    }

    /**
     * Method to create question from request.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return QAQuestion Returns the created question from the request
     *
     * @since 1.0.0
     */
    public function createQAQuestionFromRequest(RequestAbstract $request, ResponseAbstract $response, $data = null) : QAQuestion
    {
        $mardkownParser = new Markdown();

        $question = new QAQuestion();
        $question->name = (string) $request->getData('title');
        $question->question = (string) $request->getData('plain');
        $question->setLanguage((string) $request->getData('language'));
        $question->setCategory(new NullQACategory((int) $request->getData('category')));
        $question->setStatus((int) $request->getData('status'));
        $question->createdBy = new NullAccount($request->header->account);

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse, $data);
                    $question->addTag($internalResponse->get($request->uri->__toString())['response']);
                } else {
                    $question->addTag(new NullTag((int) $tag['id']));
                }
            }
        }

        return $question;
    }

    /**
     * Validate question create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQAQuestionCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['plain'] = empty($request->getData('plain')))
            || ($val['language'] = empty($request->getData('language')))
            || ($val['category'] = empty($request->getData('category')))
            || ($val['status'] = (
                $request->getData('status') !== null
                && !QAQuestionStatus::isValidValue((int) $request->getData('status'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a answer
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAAnswerCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateQAAnswerCreate($request))) {
            $response->set('qa_answer_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $answer = $this->createQAAnswerFromRequest($request);
        $this->createModel($request->header->account, $answer, QAAnswerMapper::class, 'answer', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Answer', 'Answer successfully created.', $answer);
    }

    /**
     * Method to create answer from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return QAAnswer Returns the created answer from the request
     *
     * @since 1.0.0
     */
    public function createQAAnswerFromRequest(RequestAbstract $request) : QAAnswer
    {
        $mardkownParser = new Markdown();

        $answer = new QAAnswer();
        $answer->answer = (string) $request->getData('plain');
        $answer->question = new NullQAQuestion((int) $request->getData('question'));
        $answer->setStatus((int) $request->getData('status'));
        $answer->createdBy = new NullAccount($request->header->account);

        return $answer;
    }

    /**
     * Validate answer create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQAAnswerCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['plain'] = empty($request->getData('plain')))
            || ($val['question'] = empty($request->getData('question')))
            || ($val['status'] = (
                $request->getData('status') !== null
                && !QAAnswerStatus::isValidValue((int) $request->getData('status'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a category
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQACategoryCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateQACategoryCreate($request))) {
            $response->set('qa_category_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $category = $this->createQACategoryFromRequest($request);
        $this->createModel($request->header->account, $category, QACategoryMapper::class, 'category', $request->getOrigin());

        $l11nRequest = new HttpRequest($request->uri);
        $l11nRequest->setData('category', $category->getId());
        $l11nRequest->setData('name', $request->getData('name'));
        $l11nRequest->setData('language', $request->getData('language'));

        $l11nQACategory = $this->createQACategoryL11nFromRequest($l11nRequest);
        $this->createModel($request->header->account, $l11nQACategory, QACategoryL11nMapper::class, 'tag_l11n', $request->getOrigin());

        $category->setName($l11nQACategory);

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Category', 'Category successfully created.', $category);
    }

    /**
     * Method to create category from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return QACategory Returns the created category from the request
     *
     * @since 1.0.0
     */
    public function createQACategoryFromRequest(RequestAbstract $request) : QACategory
    {
        $category = new QACategory();
        //$category->setApp(new NullQAApp((int) ($request->getData('app') ?? 1)));

        if ($request->getData('parent') !== null) {
            $category->parent = new NullQACategory((int) $request->getData('parent'));
        }

        return $category;
    }

    /**
     * Validate category create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQACategoryCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = empty($request->getData('name')))) {
            return $val;
        }

        return [];
    }

    /**
     * Validate tag l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateQACategoryL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = empty($request->getData('name')))
            || ($val['category'] = empty($request->getData('category')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create tag localization
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQACategoryL11nCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateQACategoryL11nCreate($request))) {
            $response->set('qa_category_l11n_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $l11nQACategory = $this->createQACategoryL11nFromRequest($request);
        $this->createModel($request->header->account, $l11nQACategory, QACategoryL11nMapper::class, 'qa_category_l11n', $request->getOrigin());

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Category localization successfully created', $l11nQACategory);
    }

    /**
     * Method to create tag localization from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return QACategoryL11n
     *
     * @since 1.0.0
     */
    private function createQACategoryL11nFromRequest(RequestAbstract $request) : QACategoryL11n
    {
        $l11nQACategory = new QACategoryL11n();
        $l11nQACategory->setCategory((int) ($request->getData('category') ?? 0));
        $l11nQACategory->setLanguage((string) (
            $request->getData('language') ?? $request->getLanguage()
        ));
        $l11nQACategory->name = (string) ($request->getData('name') ?? '');

        return $l11nQACategory;
    }
}
