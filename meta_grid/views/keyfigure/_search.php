<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KeyfigureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyfigure-search">

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

    <?= $form->field($model, 'formula') ?>

    <?= $form->field($model, 'aggregation') ?>

    <?= $form->field($model, 'character') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'unit') ?>

    <?= $form->field($model, 'value_range') ?>

    <?= $form->field($model, 'cumulation_possible')->checkbox() ?>

    <?= $form->field($model, 'fk_deleted_status_id') ?>

    <?= $form->field($model, 'fk_object_persistence_method_id') ?>

    <?= $form->field($model, 'fk_datamanagement_process_id') ?>

    <?= $form->field($model, 'source_definition') ?>

    <?= $form->field($model, 'source_definition_language') ?>

    <?= $form->field($model, 'source_comment') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
