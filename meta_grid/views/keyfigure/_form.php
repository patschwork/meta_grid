		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Keyfigure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyfigure-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
		echo $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'description') ?>  -->

    <?= $form->field($model, 'formula') ?>

    <?= $form->field($model, 'aggregation') ?>

    <?= $form->field($model, 'character') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'unit') ?>

    <?= $form->field($model, 'value_range') ?>

    <?= $form->field($model, 'cumulation_possible')->checkbox() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
