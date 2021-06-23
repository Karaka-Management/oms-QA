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

use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

/** \Modules\QA\Models\QAQuestion $question */
$question = $this->getData('question');

/** \Modules\QA\Models\QAAnswer[] $answers */
$answers = $question->getAnswers();

echo $this->getData('nav')->render();
?>

<div class="row qa">
    <div class="col-xs-12">
        <div class="qa-question-view">
            <div class="score">
                <div class="counter-area">
                    <div class="counter-container">
                        <i class="fa fa-chevron-up qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'); ?>"></i>
                        <span class="counter"><?= $question->getVoteScore(); ?></span>
                        <span class="text">Score</span>
                        <i class="fa fa-chevron-down qa-vote<?= $this->printHtml($question->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'); ?>"></i>
                    </div>
                    <div class="counter-container">
                        <span class="counter score<?= $this->printHtml($question->hasAccepted() ? ' done' : ' open'); ?>"><?= $question->getAnswerCount(); ?></span>
                        <span class="text">Answers</span>
                    </div>
                </div>
            </div>
            <section class="portlet">
                <div class="portlet-head"><?= $this->printHtml($question->name); ?></div>
                <div class="portlet-body">
                    <article>
                        <?= $question->question; ?>
                    </article>
                </div>
                <div class="portlet-foot qa-portlet-foot">
                    <div class="tag-list">
                        <?php $tags = $question->getTags(); foreach ($tags as $tag) : ?>
                            <span class="tag"><?= $tag->icon !== null ? '<i class="' . $this->printHtml($tag->icon ?? '') . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <a class="account-info" href="<?= UriFactory::build('{/prefix}profile/single?{?}&id=' . $question->createdBy->getId()); ?>">
                        <span class="name content"><?= $this->printHtml($question->createdBy->account->name2); ?>, <?= $this->printHtml($question->createdBy->account->name1); ?></span>
                        <?php if ($question->createdBy->image !== null && !($question->createdBy->image instanceof NullMedia)) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('{/prefix}' . $question->createdBy->image->getPath()); ?>">
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
                        <i class="fa fa-chevron-up qa-vote<?= $this->printHtml($answer->getAccountVoteScore($this->request->header->account) > 0 ? ' voted' : ' open'); ?>"></i>
                        <span class="counter"><?= $answer->getVoteScore(); ?></span>
                        <span class="text">Score</span>
                        <i class="fa fa-chevron-down qa-vote<?= $this->printHtml($answer->getAccountVoteScore($this->request->header->account) < 0 ? ' voted' : ' open'); ?>"></i>
                    </div>
                    <div class="counter-container">
                        <i class="fa fa-check qa-accept"></i>
                        <span class="text"><?= $this->printHtml($answer->isAccepted ? 'Accepted' : 'Accept'); ?></span>
                    </div>
                </div>
            </div>
            <section class="portlet">
                <div class="portlet-body">
                    <article>
                        <?= $answer->answer; ?>
                    </article>
                </div>
                <div class="portlet-foot qa-portlet-foot">
                    <a class="account-info" href="<?= UriFactory::build('{/prefix}profile/single?{?}&id=' . $answer->createdBy->getId()); ?>">
                        <span class="name content"><?= $this->printHtml($answer->createdBy->account->name2); ?> <?= $this->printHtml($answer->createdBy->account->name1); ?></span>
                        <?php if ($answer->createdBy->image !== null && !($answer->createdBy->image instanceof NullMedia)) : ?>
                            <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('{/prefix}' . $answer->createdBy->image->getPath()); ?>">
                        <?php endif; ?>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
<?php endforeach; ?>
