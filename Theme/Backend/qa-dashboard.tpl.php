<?php
/**
 * Karaka
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

use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

/** @var \Modules\QA\Modles\QAQuestion[] $questions */
$questions = $this->getData('questions');

/** @var \Modules\QA\Modles\QAApp[] $apps */
$apps = $this->getData('apps');

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12 box">
        <select name="app">
            <option value="0"><?= $this->getHtml('All'); ?>
            <?php foreach ($apps as $app) : ?>
                <option value="<?= $app->id; ?>"><?= $app->name; ?>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?php foreach ($questions as $question) : ?>
        <section class="portlet qa qa-list">
            <div class="portlet-body">
                <div class="row middle-xs">
                    <div class="counter-area">
                        <div class="counter-container">
                            <span class="counter score<?= $this->printHtml($question->hasAccepted() ? ' done' : ' open'); ?>"><?= $question->getAnswerCount(); ?></span>
                            <span class="text">Answers</span>
                        </div>
                        <div class="counter-container">
                            <span class="counter"><?= $question->getVoteScore(); ?></span>
                            <span class="text">Score</span>
                        </div>
                    </div>
                    <div class="title">
                        <a href="<?= UriFactory::build('{/base}/qa/question?{?}&id=' . $question->id); ?>"><?= $this->printHtml($question->name); ?></a>
                    </div>
                </div>
            </div>
            <div class="portlet-foot qa-portlet-foot">
                <div class="tag-list">
                    <?php $tags = $question->getTags(); foreach ($tags as $tag) : ?>
                        <span class="tag"><?= !empty($tag->icon) ? '<i class="' . $this->printHtml($tag->icon) . '"></i>' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                    <?php endforeach; ?>
                </div>

                <a class="account-info" href="<?= UriFactory::build('{/base}/profile/single?{?}&id=' . $question->createdBy->id); ?>">
                    <span class="name content"><?= $this->printHtml($question->createdBy->account->name2); ?> <?= $this->printHtml($question->createdBy->account->name1); ?></span>
                    <?php if ($question->createdBy->image->id > 0) : ?>
                        <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('{/base}/' . $question->createdBy->image->getPath()); ?>">
                    <?php endif; ?>
                </a>
            </div>
        </section>
        <?php endforeach; ?>
        <?php if (empty($questions)) : ?>
            <div class="emptyPage"></div>
        <?php endif; ?>
    </div>
</div>
