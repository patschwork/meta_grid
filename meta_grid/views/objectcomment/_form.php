<?php
// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>	
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Objectcomment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objectcomment-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

    <?= $form->field($model, 'ref_fk_object_id')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id')->textInput() ?>

	<?php
		echo $form->field($model, 'comment')->widget(floor12\summernote\Summernote::class);	?>
     <?php // $form->field($model, 'comment') ?>

    <?= $form->field($model, 'created_at_datetime') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
