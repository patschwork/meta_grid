<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SchedulingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scheduling-search">

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

    <?= $form->field($model, 'fk_tool_id') ?>

    <?= $form->field($model, 'scheduling_series') ?>

    <?= $form->field($model, 'fk_deleted_status_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
