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

use phpOMS\Uri\UriFactory;

$question = $this->getData('question');
$answers  = $question->getAnswers();

echo $this->getData('nav')->render();
?>

<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <header><h1><?= $this->printHtml($question->name); ?></h1></header>
            <div class="inner">
                <?= $this->printHtml($question->question); ?>
            </div>
            <div class="inner">
                <?php if ($question->createdBy->image !== null) : ?>
                <img alt="<?= $this->getHtml('AccountImage', '0', '0'); ?>" loading="lazy" src="<?= UriFactory::build('{/prefix}' . $question->createdBy->image->getPath()); ?>">
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php foreach ($answers as $answer) : ?>
<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <div class="inner">
                <?= $this->printHtml($answer->getAnswer()); ?><?= $this->printHtml($answer->createdAt->format('Y-m-d')); ?><?= $answer->createdBy->getId(); ?><?= $answer->getStatus(); ?><?= $this->printHtml((string) $answer->isAccepted()); ?>
            </div>
        </section>
    </div>
</div>
<?php endforeach; ?>
