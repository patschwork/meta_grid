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

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'formula') ?>

    <?php // echo $form->field($model, 'aggregation') ?>

    <?php // echo $form->field($model, 'character') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'value_range') ?>

    <?php // echo $form->field($model, 'cumulation_possible')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
