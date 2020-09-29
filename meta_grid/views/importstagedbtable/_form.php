		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\ImportStageDbTable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="import-stage-db-table-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'client_name') ?>

    <?= $form->field($model, 'project_name') ?>

    <?= $form->field($model, 'db_table_name') ?>

    <?= $form->field($model, 'db_table_description') ?>

    <?= $form->field($model, 'db_table_field_name') ?>

    <?= $form->field($model, 'db_table_field_datatype') ?>

    <?= $form->field($model, 'db_table_field_description') ?>

    <?= $form->field($model, 'db_table_type_name') ?>

    <?= $form->field($model, 'db_table_context_name') ?>

    <?= $form->field($model, 'db_table_context_prefix') ?>

    <?= $form->field($model, 'isPrimaryKeyField')->checkbox() ?>

    <?= $form->field($model, 'isForeignKeyField')->checkbox() ?>

    <?= $form->field($model, 'foreignKey_table_name') ?>

    <?= $form->field($model, 'foreignKey_table_field_name') ?>

    <?= $form->field($model, '_import_state')->textInput() ?>

    <?= $form->field($model, '_import_date') ?>

    <?= $form->field($model, 'is_BusinessKey')->checkbox() ?>

    <?= $form->field($model, 'is_GDPR_relevant')->checkbox() ?>

    <?= $form->field($model, 'location') ?>

    <?= $form->field($model, 'database_or_catalog')->textInput(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'schema')->textInput(['maxlength' => 4000]) ?>

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_db_database_id')->dropDownList($db_databaseList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_db_database_id')->textInput() ?>  -->

    <?= $form->field($model, 'column_default_value')->textInput(['maxlength' => 1000]) ?>

    <?= $form->field($model, 'column_cant_be_null')->checkbox() ?>

    <?= $form->field($model, 'additional_field_1')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_2')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_3')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_4')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_5')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_6')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_7')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_8')->textInput(['maxlength' => 4000]) ?>

    <?= $form->field($model, 'additional_field_9')->textInput(['maxlength' => 4000]) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
