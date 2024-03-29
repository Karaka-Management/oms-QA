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

use phpOMS\Uri\UriFactory;

/** @var \Modules\QA\Models\QAQuestion $question */
$question = $this->data['question'];

/** @var \Modules\QA\Models\QAAnswer[] $answers */
$answers = $question->getAnswersByScore();

/** @var array $scores */
$scores = $this->data['scores'];

echo $this->data['nav']->render();
?>

<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view">
            <div class="score">
                <div class="counter-area">
                    <div class="counter-container">
                        <?php if ($this->request->header->account !== $question->createdBy->account->id) : ?>
                        <a id="qa-question-upvote" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "event.prevent"},
                                    {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/api}qa/question/vote?id=' . $question->id);?>&type=1", "method": "PUT", "request_type": "json"},
                                    {"key": 3, "type": "dom.reload", "delay": 0}
                                ]
                            }
                        ]' href="#">
                            <i class="g-icon qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'); ?>">expand_less</i>
                        </a>
                        <?php endif; ?>
                        <span class="counter"><?= $question->getVoteScore(); ?></span>
                        <span class="text"><?= $this->getHtml('Score'); ?></span>
                        <?php if ($this->request->header->account !== $question->createdBy->account->id) : ?>
                        <a id="qa-question-downvote" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "event.prevent"},
                                    {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/api}qa/question/vote?id=' . $question->id);?>&type=-1", "method": "PUT", "request_type": "json"},
                                    {"key": 3, "type": "dom.reload", "delay": 0}
                                ]
                            }
                        ]' href="#">
                            <i class="g-icon qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'); ?>">expand_more</i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="counter-container">
                        <span class="counter score<?= $this->printHtml($question->hasAccepted() ? ' done' : ' open'); ?>"><?= $question->getAnswerCount(); ?></span>
                        <span class="text"><?= $this->getHtml('Answers'); ?></span>
                    </div>
                </div>
            </div>
            <section class="portlet wf-100">
                <div class="portlet-head"><?= $this->printHtml($question->name); ?></div>
                <div class="portlet-body">
                    <?php if ($question->id === 0) : ?>
                        <textarea name="comment"></textarea>
                    <?php else : ?>
                        <article><?= $question->question; ?></article>
                    <?php endif; ?>
                </div>
                <div class="portlet-foot qa-portlet-foot">
                    <div class="tag-list">
                        <?php
                            foreach ($question->tags as $tag) :
                                if ($tag->id === 0) { continue; }
                        ?>
                            <span class="tag">
                                <?= empty($tag->icon) ? '' : '<i class="g-icon">' . $this->printHtml($tag->icon) . '</i>'; ?>
                                <?= $this->printHtml($tag->getL11n()); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>

                    <?php $files = $question->files; foreach ($files as $file) : ?>
                         <span><a class="content" href="<?= UriFactory::build('{/base}/media/view?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                    <?php endforeach; ?>

                    <a class="account-info" href="<?= UriFactory::build('{/base}/profile/view?{?}&id=' . $question->createdBy->id); ?>">
                        <span class="name">
                            <div class="content"><?= $this->printHtml($question->createdBy->account->name2); ?> <?= $this->printHtml($question->createdBy->account->name1); ?></div>
                            <div class="name-score"><?= $this->getHtml('Score'); ?>: <?= $scores[$question->createdBy->account->id] ?? 0; ?></div>
                        </span>

                        <?php if ($question->createdBy->image->id > 0) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build($question->createdBy->image->getPath()); ?>">
                        <?php endif; ?>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>

<?php if ($question->id !== 0) : ?>
<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view">
            <div class="score"><div class="counter-area"></div></div>
            <section class="portlet wf-100">
                <form id="answerCreate" class="Comments_create" method="PUT" action="<?= UriFactory::build('{/api}qa/answer/create?question=' . $question->id . '&csrf={$CSRF}'); ?>">
                    <div class="portlet-head"><?= $this->getHtml('Answer'); ?></div>
                    <div class="portlet-body">
                        <textarea name="comment"></textarea>
                    </div>
                    <div class="portlet-foot">
                        <input type="submit" name="createButton" id="iCreateButton" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<?php endif; ?>

<?php foreach ($answers as $answer) : ?>
<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view<?= $this->printHtml($answer->isAccepted ? ' accepted' : ''); ?>">
            <div class="score">
                <div class="counter-area">
                    <div class="counter-container">
                        <?php if ($this->request->header->account !== $answer->createdBy->account->id) : ?>
                        <a id="qa-answer-upvote-<?= $answer->id; ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/api}qa/answer/vote?id=' . $answer->id);?>&type=1", "method": "PUT", "request_type": "json"},
                                        {"key": 3, "type": "dom.reload", "delay": 0}
                                    ]
                                }
                            ]' href="#">
                            <i class="g-icon qa-vote<?= $answer->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'; ?>">expand_less</i>
                        </a>
                        <?php endif; ?>
                        <span class="counter"><?= $answer->getVoteScore(); ?></span>
                        <span class="text"><?= $this->getHtml('Score'); ?></span>
                        <?php if ($this->request->header->account !== $answer->createdBy->account->id) : ?>
                        <a id="qa-answer-downvote-<?= $answer->id; ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/api}qa/answer/vote?id=' . $answer->id);?>&type=-1", "method": "PUT", "request_type": "json"},
                                        {"key": 3, "type": "dom.reload", "delay": 0}
                                    ]
                                }
                        ]' href="#">
                            <i class="g-icon qa-vote<?= $answer->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'; ?>">expand_more</i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="counter-container">
                        <?php if ($this->request->header->account === $question->createdBy->account->id) : ?>
                        <a id="qa-answer-accept-<?= $answer->id; ?>" data-action='[
                                {
                                    "key": 1, "listener": "click", "action": [
                                        {"key": 1, "type": "event.prevent"},
                                        {"key": 2, "type": "message.request", "uri": "<?= UriFactory::build('{/api}qa/answer/accept?id=' . $answer->id);?>&type=1", "method": "PUT", "request_type": "json"},
                                        {"key": 3, "type": "dom.reload", "delay": 0}
                                    ]
                                }
                        ]' href="#">
                        <?php endif; ?>
                            <i class="g-icon qa-accept">check</i>
                        <?php if ($this->request->header->account === $question->createdBy->account->id) : ?>
                        </a>
                        <?php endif; ?>
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
                    <?php $files = $answer->files; foreach ($files as $file) : ?>
                        <span><a class="content" href="<?= UriFactory::build('{/base}/media/view?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                    <?php endforeach; ?>

                    <a class="account-info" href="<?= UriFactory::build('{/base}/profile/view?{?}&id=' . $answer->createdBy->id); ?>">
                        <span class="name">
                            <div class="content"><?= $this->printHtml($answer->createdBy->account->name2); ?> <?= $this->printHtml($answer->createdBy->account->name1); ?></div>
                            <div class="name-score"><?= $this->getHtml('Score'); ?>: <?= $scores[$answer->createdBy->account->id] ?? 0; ?></div>
                        </span>
                        <?php if ($answer->createdBy->image->id > 0) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build($answer->createdBy->image->getPath()); ?>">
                        <?php endif; ?>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endforeach; ?>
