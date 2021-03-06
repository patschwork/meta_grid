		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tool-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_tool_type_id')->dropDownList($tool_typeList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_tool_type_id')->textInput() ?>  -->

    <?= $form->field($model, 'tool_name') ?>

    <?= $form->field($model, 'vendor') ?>

    <?= $form->field($model, 'version') ?>

	<?php
		echo $form->field($model, 'comment')->widget(\yii\redactor\widgets\Redactor::className());	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'comment') ?>  -->



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
