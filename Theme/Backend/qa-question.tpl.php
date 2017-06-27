<?php
$question = $this->getData('question');
$answers = $question->getAnswers();

echo $this->getData('nav')->render(); 
?>

<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <header><h1><?= $question->getName(); ?></h1></header>
            <div class="inner">
                <?= $question->getQuestion(); ?>
            </div>
        </section>
    </div>
</div>

<?php foreach($answers as $answer) : ?>
<div class="row">
    <div class="col-xs-12">
        <section class="box wf-100">
            <div class="inner">
                <?= $answer->getAnswer(); ?><?= $answer->getCreatedAt()->format('Y-m-d'); ?><?= $answer->getCreatedBy(); ?><?= $answer->getStatus(); ?><?= $answer->isAccepted(); ?>
            </div>
        </section>
    </div>
</div>
<?php endforeach; ?>