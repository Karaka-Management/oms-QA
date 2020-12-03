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

$questions = $this->getData('questions');
echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <?php foreach ($questions as $question) : ?>
        <section class="portlet qa-list">
            <div class="portlet-body">
                <div class="row middle-xs">
                    <div class="col-xs-1 scores">
                        <span class="score<?= $this->printHtml($question->hasAccepted() ? ' done' : ''); ?>"><?= $this->printHtml(\count($question->getAnswers())); ?></span>
                    </div>
                    <div class="title col-xs-11">
                        <a href="<?= \phpOMS\Uri\UriFactory::build('{/prefix}qa/question?{?}&id=' . $question->getId()); ?>"><?= $this->printHtml($question->name); ?></a>
                    </div>
                </div>
                <div class="tags">
                <?php $tags = $question->getTags(); foreach ($tags as $tag) : ?>
                    <span class="tag red"><?= $this->printHtml($tag->getTitle()); ?></span>
                <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endforeach; ?>
    </div>
</div>
