<?php

/** @var yii\web\View $this */
/** @var \common\models\Page $page */

use common\models\Page;

//$this->title = $model->title;
$this->title = $page->title;
?>
<div class="site-index">
    <div class="body-content">
        <h3> <?= $page->title ?> </h3>
        <div>
            <img width="256" height="256" src="//<?= Yii::$app->urlManagerBackend->hostInfo.'/uploads/'.$page->image; ?>" />
        </div>
        <?= $page->text; ?>
    </div>
</div>
