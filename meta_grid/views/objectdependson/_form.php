		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ObjectDependsOn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-depends-on-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

    <?= $form->field($model, 'ref_fk_object_id_parent')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_parent')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_id_child')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_child')->textInput() ?>

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_object_persistence_method_id')->dropDownList($object_persistence_methodList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_persistence_method_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_datamanagement_process_id')->dropDownList($datamanagement_processList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_datamanagement_process_id')->textInput() ?>  -->



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
