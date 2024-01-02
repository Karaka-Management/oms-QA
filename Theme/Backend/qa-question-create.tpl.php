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
            <section class="portlet wf-100">
                <form id="questionCreate" method="PUT" action="<?= UriFactory::build('{/api}qa/question/create?csrf={$CSRF}'); ?>">
                    <div class="portlet-head"><?= $this->getHtml('Question'); ?></div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <label for="iTitle"><?= $this->getHtml('Title'); ?></label>
                            <input id="iTitle" type="text" name="title" value="" />
                        </div>

                        <div class="form-group">
                            <label for="iQuestion"><?= $this->getHtml('Question'); ?></label>
                            <textarea id="iQuestion" name="plain"></textarea>
                        </div>
                    </div>
                    <div class="portlet-foot qa-portlet-foot">
                        <div class="tag-list">
                            <?php
                                $tags = $question->getTags();
                                foreach ($tags as $tag) :
                                    if ($tag->id === 0) { continue; }
                            ?>
                                <span class="tag"><?= empty($tag->icon) ? '' : ''; ?><?= $this->printHtml($tag->getL11n()); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <?php $files = $question->getMedia(); foreach ($files as $file) : ?>
                            <span><a class="content" href="<?= UriFactory::build('{/base}/media/single?id=' . $file->id);?>"><?= $file->name; ?></a></span>
                        <?php endforeach; ?>

                        <input type="submit" name="createButton" id="iCreateButton" value="<?= $this->getHtml('Create', '0', '0'); ?>">

                        <a class="account-info" href="<?= UriFactory::build('{/base}/profile/single?{?}&id=' . $question->createdBy->id); ?>">
                            <span class="name">
                                <div class="content"><?= $this->printHtml($question->createdBy->account->name2); ?> <?= $this->printHtml($question->createdBy->account->name1); ?></div>
                                <div class="name-score"><?= $this->getHtml('Score'); ?>: <?= $scores[$question->createdBy->account->id] ?? 0; ?></div>
                            </span>

                            <?php if ($question->createdBy->image->id > 0) : ?>
                                <img width="40px" alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build($question->createdBy->image->getPath()); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>