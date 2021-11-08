		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DbDatabase */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="db-database-form">

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

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_tool_id')->dropDownList($toolList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_tool_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_deleted_status_id')->dropDownList($deleted_statusList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_deleted_status_id')->textInput() ?>  -->

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

    <?= $form->field($model, 'source_definition') ?>

    <?= $form->field($model, 'source_definition_language') ?>

    <?= $form->field($model, 'source_comment') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
