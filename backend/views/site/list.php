<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\CreatePage $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use dosamigos\ckeditor\CKEditor;
use common\models\Page;
use yii\web\UploadedFile;

$this->title = 'My Pages';
$pages = $model->list();
?>
<div class="my-pages">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php foreach($pages as $page){ ?>
            
            <div class="form-group">
                <p> <a href="//<?= Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/page', 'url' => $page['pretty_url']]); ?> "><?= $page['title'] ?> </a></p>
                <div class="btn-group-sm d-inline">
                    
                    <?php $form_delete = ActiveForm::begin(['id' => 'edit-pages', 'options' => ['class' => 'd-inline']]); ?>
                        <input type="hidden" name="type" value="edit" />
                        <input type="hidden" name="id" value="<?=$page->id?>"/>
                        <?= Html::submitButton('Edit', ['class' => 'btn btn-sm btn-success btn-inline m-1', 'name' => 'edit-button']) ?>
                    <?php ActiveForm::end(); ?>
                    
                    <?php $form_delete = ActiveForm::begin(['id' => 'my-pages', 'options' => ['class' => 'd-inline']]); ?>
                        <input type="hidden" name="type" value="delete" />
                        <input type="hidden" name="id" value="<?=$page->id?>"/>
                        <?= Html::submitButton('Delete', ['class' => 'btn btn-sm btn-danger btn-inline m-1', 'name' => 'delete-button']) ?>
                    <?php ActiveForm::end(); ?>

                </div>
            <div>

        <?php } ?>
        

    </div>
</div>
