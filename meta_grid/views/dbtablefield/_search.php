<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DbTableFieldSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="db-table-field-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'fk_object_type_id') ?>

    <?= $form->field($model, 'fk_project_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'fk_db_table_id') ?>

    <?= $form->field($model, 'datatype') ?>

    <?= $form->field($model, 'bulk_load_checksum') ?>

    <?= $form->field($model, 'fk_deleted_status_id') ?>

    <?= $form->field($model, 'is_PrimaryKey')->checkbox() ?>

    <?= $form->field($model, 'is_BusinessKey')->checkbox() ?>

    <?= $form->field($model, 'is_GDPR_relevant')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
