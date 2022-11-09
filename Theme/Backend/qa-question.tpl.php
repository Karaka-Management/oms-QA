<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\QA
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

/** @var \Modules\QA\Models\QAQuestion $question */
$question = $this->getData('question');

/** @var \Modules\QA\Models\QAAnswer[] $answers */
$answers = $question->getAnswersByScore();

/** @var array $scores */
$scores = $this->getData('scores');

echo $this->getData('nav')->render();
?>

<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view">
            <div class="score">
                <div class="counter-area">
                    <div class="counter-container">
                        <a id="qa-question-upvote" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "event.prevent"},
                                    {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/base}/{/lang}/{/api}qa/question/vote?id=' . $question->getId());?>&type=1", "method": "PUT", "request_type": "json"}
                                ]
                            }
                        ]' href="#">
                            <i class="fa fa-chevron-up qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'); ?>"></i>
                        </a>
                        <span class="counter"><?= $question->getVoteScore(); ?></span>
                        <span class="text">Score</span>
                        <a id="qa-question-downvote" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "event.prevent"},
                                    {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/base}/{/lang}/{/api}qa/question/vote?id=' . $question->getId());?>&type=-1", "method": "PUT", "request_type": "json"}
                                ]
                            }
                        ]' href="#">
                            <i class="fa fa-chevron-down qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'); ?>"></i>
                        </a>
                    </div>
                    <div class="counter-container">
                        <span class="counter score<?= $this->printHtml($question->hasAccepted() ? ' done' : ' open'); ?>"><?= $question->getAnswerCount(); ?></span>
                        <span class="text">Answers</span>
                    </div>
                </div>
            </div>
            <section class="portlet wf-100">
                <div class="portlet-head"><?= $this->printHtml($question->name); ?></div>
                <div class="portlet-body">
                    <article><?= $question->question; ?></article>
                </div>
                <div class="portlet-foot qa-portlet-foot">
                    <div class="tag-list">
                        <?php $tags = $question->getTags(); foreach ($tags as $tag) : ?>
                            <span class="tag"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <?php $files = $question->getMedia(); foreach ($files as $file) : ?>
                         <span><a class="content" href="<?= UriFactory::build('media/single?id=' . $file->getId());?>"><?= $file->name; ?></a></span>
                    <?php endforeach; ?>

                    <a class="account-info" href="<?= UriFactory::build('profile/single?{?}&id=' . $question->createdBy->getId()); ?>">
                        <span class="name">
                            <div class="content"><?= $this->printHtml($question->createdBy->account->name2); ?> <?= $this->printHtml($question->createdBy->account->name1); ?></div>
                            <div class="name-score">Score: <?= $scores[$question->createdBy->account->getId()] ?? 0; ?></div>
                        </span>

                        <?php if ($question->createdBy->image !== null && !($question->createdBy->image instanceof NullMedia)) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('' . $question->createdBy->image->getPath()); ?>">
                        <?php endif; ?>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

<?php foreach ($answers as $answer) : ?>
<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view<?= $this->printHtml($answer->isAccepted ? ' accepted' : ''); ?>">
            <div class="score">
                <div class="counter-area">
                    <div class="counter-container">
                        <a id="qa-answer-upvote-<?= $answer->getId(); ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/base}/{/lang}/{/api}qa/answer/vote?id=' . $answer->getId());?>&type=1", "method": "PUT", "request_type": "json"}
                                    ]
                                }
                            ]' href="#">
                            <i class="fa fa-chevron-up qa-vote<?= $this->printHtml($answer->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'); ?>"></i>
                        </a>
                        <span class="counter"><?= $answer->getVoteScore(); ?></span>
                        <span class="text">Score</span>
                        <a id="qa-answer-downvote-<?= $answer->getId(); ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/base}/{/lang}/{/api}qa/answer/vote?id=' . $answer->getId());?>&type=-1", "method": "PUT", "request_type": "json"}
                                    ]
                                }
                        ]' href="#">
                            <i class="fa fa-chevron-down qa-vote<?= $this->printHtml($answer->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'); ?>"></i>
                        </a>
                    </div>
                    <div class="counter-container">
                        <a id="qa-answer-accept-<?= $answer->getId(); ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/base}/{/lang}/{/api}qa/answer/accept?id=' . $answer->getId());?>&type=1", "method": "PUT", "request_type": "json"}
                                    ]
                                }
                        ]' href="#">
                            <i class="fa fa-check qa-accept"></i>
                        </a>
                        <span class="text"><?= $this->printHtml($answer->isAccepted ? 'Accepted' : 'Accept'); ?></span>
                    </div>
                </div>
            </div>
            <section class="portlet wf-100">
                <div class="portlet-body">
                    <article>
                        <?= $answer->answer; ?>
                    </article>
                </div>
                <div class="portlet-foot qa-portlet-foot">
                    <?php $files = $answer->getMedia(); foreach ($files as $file) : ?>
                        <span><a class="content" href="<?= UriFactory::build('media/single?id=' . $file->getId());?>"><?= $file->name; ?></a></span>
                    <?php endforeach; ?>

                    <a class="account-info" href="<?= UriFactory::build('profile/single?{?}&id=' . $answer->createdBy->getId()); ?>">
                        <span class="name">
                            <div class="content"><?= $this->printHtml($answer->createdBy->account->name2); ?> <?= $this->printHtml($answer->createdBy->account->name1); ?></div>
                            <div class="name-score">Score: <?= $scores[$answer->createdBy->account->getId()] ?? 0; ?></div>
                        </span>
                        <?php if ($answer->createdBy->image !== null && !($answer->createdBy->image instanceof NullMedia)) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('' . $answer->createdBy->image->getPath()); ?>">
                        <?php endif; ?>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endforeach; ?>
