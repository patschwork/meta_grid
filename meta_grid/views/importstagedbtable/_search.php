<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ImportStageDbTableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="import-stage-db-table-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

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

    <?= $form->field($model, '_import_state') ?>

    <?= $form->field($model, '_import_date') ?>

    <?= $form->field($model, 'is_BusinessKey')->checkbox() ?>

    <?= $form->field($model, 'is_GDPR_relevant')->checkbox() ?>

    <?= $form->field($model, 'location') ?>

    <?= $form->field($model, 'database_or_catalog') ?>

    <?= $form->field($model, 'schema') ?>

    <?= $form->field($model, 'fk_project_id') ?>

    <?= $form->field($model, 'fk_db_database_id') ?>

    <?php // echo $form->field($model, 'column_default_value') ?>

    <?php // echo $form->field($model, 'column_cant_be_null')->checkbox() ?>

    <?php // echo $form->field($model, 'additional_field_1') ?>

    <?php // echo $form->field($model, 'additional_field_2') ?>

    <?php // echo $form->field($model, 'additional_field_3') ?>

    <?php // echo $form->field($model, 'additional_field_4') ?>

    <?php // echo $form->field($model, 'additional_field_5') ?>

    <?php // echo $form->field($model, 'additional_field_6') ?>

    <?php // echo $form->field($model, 'additional_field_7') ?>

    <?php // echo $form->field($model, 'additional_field_8') ?>

    <?php // echo $form->field($model, 'additional_field_9') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
