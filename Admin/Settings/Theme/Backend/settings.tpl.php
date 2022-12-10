<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Auditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View         $this
 * @var \Modules\QA\Models\QAApp[] $apps
 */
$apps = $this->getData('apps') ?? [];

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Apps'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="appList" class="default sticky">
                <thead>
                <tr>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="appList-sort-1">
                            <input type="radio" name="appList-sort" id="appList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="appList-sort-2">
                            <input type="radio" name="appList-sort" id="appList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Name'); ?>
                        <label for="appList-sort-3">
                            <input type="radio" name="appList-sort" id="appList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="appList-sort-4">
                            <input type="radio" name="appList-sort" id="appList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php $count = 0;
                foreach ($apps as $key => $app) : ++$count;
                    $url = UriFactory::build('{/lang}/{/app}/admin/module/settings?id=QA&app=' . $app->id); ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><?= $app->getId(); ?></a>
                        <td><a href="<?= $url; ?>"><?= $this->printHtml($app->name); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="8" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </div>
    </div>
</div>
