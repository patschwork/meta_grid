		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DbTableField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="db-table-field-form">

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
	if (isset($fk_db_table_id))
	{
		echo $form->field($model, 'fk_db_table_id')->dropDownList($db_tableList, ['id'=>'name', 'options' => [ $fk_db_table_id => ['selected ' => true]]]);
	}
	else
	{
		echo $form->field($model, 'fk_db_table_id')->dropDownList($db_tableList, ['id'=>'name']);
	}
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_db_table_id')->textInput() ?>  -->

    <?= $form->field($model, 'datatype') ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
