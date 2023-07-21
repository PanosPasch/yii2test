<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\CreatePage $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use dosamigos\ckeditor\CKEditor;
use common\models\Page;
use yii\web\UploadedFile;

$this->title = 'Create Page';
?>
<div class="create-page">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please enter new page details:</p>

        <?php $form = ActiveForm::begin(['id' => 'create-page', 'options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'pretty_url')->textInput() ?>

            <?= $form->field($model, 'category')->dropDownList(Page::CATEGORY, []) ?>

            <?= $form->field($model, 'image')->fileInput() ?>

            <?= $form->field($model, 'text')->widget(CKEditor::classname(), [

            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Create', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
