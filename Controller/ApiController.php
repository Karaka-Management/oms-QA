<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\QA\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\NullMedia;
use Modules\Profile\Models\Profile;
use Modules\QA\Models\NullQAApp;
use Modules\QA\Models\NullQAQuestion;
use Modules\QA\Models\PermissionCategory;
use Modules\QA\Models\QAAnswer;
use Modules\QA\Models\QAAnswerMapper;
use Modules\QA\Models\QAAnswerStatus;
use Modules\QA\Models\QAAnswerVote;
use Modules\QA\Models\QAAnswerVoteMapper;
use Modules\QA\Models\QAApp;
use Modules\QA\Models\QAAppMapper;
use Modules\QA\Models\QAQuestion;
use Modules\QA\Models\QAQuestionMapper;
use Modules\QA\Models\QAQuestionStatus;
use Modules\QA\Models\QAQuestionVote;
use Modules\QA\Models\QAQuestionVoteMapper;
use Modules\Tag\Models\NullTag;
use phpOMS\Account\PermissionType;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * QA api controller class.
 *
 * @package Modules\QA
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a question
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQuestionUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to create a question
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAAppUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to create a question
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAnswerUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to create a question
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAQuestionCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQAQuestionCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $question = $this->createQAQuestionFromRequest($request, $response, $data);
        $this->createModel($request->header->account, $question, QAQuestionMapper::class, 'question', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $question);
    }

    /**
     * Method to create question from request.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return QAQuestion Returns the created question from the request
     *
     * @since 1.0.0
     */
    public function createQAQuestionFromRequest(RequestAbstract $request, ResponseAbstract $response, $data = null) : QAQuestion
    {
        $question              = new QAQuestion();
        $question->name        = (string) $request->getData('title');
        $question->questionRaw = (string) $request->getData('plain');
        $question->question    = Markdown::parse($request->getDataString('plain') ?? '');
        $question->app         = new NullQAApp($request->getDataInt('app') ?? 1);
        $question->setLanguage((string) $request->getData('language'));
        $question->setStatus((int) $request->getData('status'));
        $question->createdBy = new Profile(new NullAccount($request->header->account));

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('icon', $tag['icon'] ?? null, true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse);

                    if (!\is_array($data = $internalResponse->getDataArray($request->uri->__toString()))) {
                        continue;
                    }

                    $question->addTag($data['response']);
                } else {
                    $question->addTag(new NullTag((int) $tag['id']));
                }
            }
        }

        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                [],
                [],
                $uploadedFiles,
                $request->header->account,
                __DIR__ . '/../../../Modules/Media/Files/Modules/QA',
                '/Modules/QA',
            );

            foreach ($uploaded as $media) {
                $question->addMedia($media);
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
            foreach ($mediaFiles as $media) {
                $question->addMedia(new NullMedia($media));
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
        if (($val['title'] = !$request->hasData('title'))
            || ($val['plain'] = !$request->hasData('plain'))
            || ($val['language'] = !$request->hasData('language'))
            || ($val['status'] = (
                $request->hasData('status')
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAAnswerCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQAAnswerCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $answer = $this->createQAAnswerFromRequest($request);
        $this->createModel($request->header->account, $answer, QAAnswerMapper::class, 'answer', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $answer);
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
        $answer             = new QAAnswer();
        $answer->answerRaw  = (string) $request->getData('plain');
        $answer->answer     = Markdown::parse($request->getDataString('plain') ?? '');
        $answer->question   = new NullQAQuestion((int) $request->getData('question'));
        $answer->isAccepted = false;
        $answer->setStatus((int) $request->getData('status'));
        $answer->createdBy = new Profile(new NullAccount($request->header->account));

        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                [],
                [],
                $uploadedFiles,
                $request->header->account,
                __DIR__ . '/../../../Modules/Media/Files/Modules/QA',
                '/Modules/QA',
            );

            foreach ($uploaded as $media) {
                $answer->addMedia($media);
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
            foreach ($mediaFiles as $media) {
                $answer->addMedia(new NullMedia($media));
            }
        }

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
        if (($val['plain'] = !$request->hasData('plain'))
            || ($val['question'] = !$request->hasData('question'))
            || ($val['status'] = (
                $request->hasData('status')
                && !QAAnswerStatus::isValidValue((int) $request->getData('status'))
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiChangeAnsweredStatus(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQAAnswerStatusUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\QA\Models\QAAnswer $newAccepted */
        $newAccepted    = QAAnswerMapper::get()->with('profile')->where('id', (int) $request->getData('id'))->execute();
        $oldNewAccepted = clone $newAccepted;

        /** @var \Modules\QA\Models\QAQuestion $question */
        $question = QAQuestionMapper::get()->where('id', $oldNewAccepted->question->id)->execute();
        if ($question->createdBy->account->id !== $request->header->account
            && !$this->app->accountManager->get($request->header->account)
                ->hasPermission(PermissionType::CREATE, $this->app->unitId, null, self::NAME, PermissionCategory::ACCEPT)
        ) {
            $response->header->status = RequestStatusCode::R_403;
            $this->createInvalidUpdateResponse($request, $response, null);

            return;
        }

        /** @var \Modules\QA\Models\QAAnswer $oldAccepted */
        $oldAccepted = QAAnswerMapper::get()
            ->where('question', $oldNewAccepted->question->id)
            ->where('isAccepted', true)
            ->execute();

        if ($oldAccepted->id !== 0 &&
            $oldNewAccepted->id !== $oldAccepted->id
        ) {
            $oldUnaccepted             = clone $oldAccepted;
            $oldUnaccepted->isAccepted = !$oldUnaccepted->isAccepted;

            $this->updateModel($request->header->account, $oldAccepted, $oldUnaccepted, QAAnswerMapper::class, 'answer', $request->getOrigin());
        }

        $newAccepted = $this->updateAnsweredStatusFromRequest($request, $newAccepted);
        $this->updateModel($request->header->account, $oldNewAccepted, $newAccepted, QAAnswerMapper::class, 'answer', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $newAccepted);
    }

    /**
     * Validate answer vote change request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQAAnswerStatusUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create category from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return QAAnswer
     *
     * @since 1.0.0
     */
    public function updateAnsweredStatusFromRequest(RequestAbstract $request, QAAnswer $answer) : QAAnswer
    {
        $answer->isAccepted = !$answer->isAccepted;

        return $answer;
    }

    /**
     * Api method to create a category
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQAAppCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQAAppCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $app = $this->createQAAppFromRequest($request);
        $this->createModel($request->header->account, $app, QAAppMapper::class, 'app', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $app);
    }

    /**
     * Method to create app from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return QAApp Returns the created app from the request
     *
     * @since 1.0.0
     */
    public function createQAAppFromRequest(RequestAbstract $request) : QAApp
    {
        $app       = new QAApp();
        $app->name = $request->getDataString('name') ?? '';
        $app->unit = $request->getDataInt('unit');

        return $app;
    }

    /**
     * Validate app create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQAAppCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to change question vote
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiChangeQAQuestionVote(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQuestionVote($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\QA\Models\QAQuestionVote $questionVote */
        $questionVote = QAQuestionVoteMapper::get()
            ->where('question', (int) $request->getData('id'))
            ->where('createdBy', $request->header->account)
            ->execute();

        if ($questionVote->id === 0) {
            /** @var \Modules\QA\Models\QAQuestion $question */
            $question = QAQuestionMapper::get()->where('id', (int) $request->getData('id'))->execute();

            $new             = new QAQuestionVote();
            $new->score      = (int) $request->getData('type');
            $new->question   = (int) $request->getData('id');
            $new->createdBy  = new NullAccount($request->header->account);
            $new->createdFor = $question->createdBy->id;

            $this->createModel($request->header->account, $new, QAQuestionVoteMapper::class, 'qa_question_vote', $request->getOrigin());
            $this->createStandardUpdateResponse($request, $response, $new);
        } else {
            /** @var QAQuestionVote $questionVote */
            $new        = clone $questionVote;
            $new->score = ((int) $request->getData('type')) === $new->score
                ? 0
                : (int) $request->getData('type');

            $this->updateModel($request->header->account, $questionVote, $new, QAQuestionVoteMapper::class, 'qa_question_vote', $request->getOrigin());
            $this->createStandardUpdateResponse($request, $response, $new);
        }
    }

    /**
     * Validate question vote request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQuestionVote(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = (!$request->hasData('id')))
            || ($val['type'] = ($request->getDataInt('type') < -1 || $request->getData('type') > 1))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to change answer vote
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiChangeQAAnswerVote(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAnswerVote($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\QA\Models\QAAnswerVote $answerVote */
        $answerVote = QAAnswerVoteMapper::get()
            ->where('answer', (int) $request->getData('id'))
            ->where('createdBy', $request->header->account)
            ->execute();

        if ($answerVote->id === 0) {
            /** @var \Modules\QA\Models\QAAnswer $answer */
            $answer = QAAnswerMapper::get()->where('id', (int) $request->getData('id'))->execute();

            $new             = new QAAnswerVote();
            $new->score      = (int) $request->getData('type');
            $new->answer     = (int) $request->getData('id');
            $new->createdBy  = new NullAccount($request->header->account);
            $new->createdFor = $answer->createdBy->id;

            $this->createModel($request->header->account, $new, QAAnswerVoteMapper::class, 'qa_answer_vote', $request->getOrigin());
            $this->createStandardUpdateResponse($request, $response, $new);
        } else {
            /** @var QAAnswerVote $answerVote */
            $new        = clone $answerVote;
            $new->score = ((int) $request->getData('type')) === $new->score
                ? 0
                : (int) $request->getData('type');

            $this->updateModel($request->header->account, $answerVote, $new, QAAnswerVoteMapper::class, 'qa_answer_vote', $request->getOrigin());
            $this->createStandardUpdateResponse($request, $response, $new);
        }
    }

    /**
     * Validate answer vote request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateAnswerVote(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = (!$request->hasData('id')))
            || ($val['type'] = ($request->getDataInt('type') < -1 || $request->getData('type') > 1))
        ) {
            return $val;
        }

        return [];
    }
}
