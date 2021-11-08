<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ToolSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tool-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'fk_tool_type_id') ?>

    <?= $form->field($model, 'tool_name') ?>

    <?= $form->field($model, 'vendor') ?>

    <?= $form->field($model, 'version') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'fk_object_persistence_method_id') ?>

    <?= $form->field($model, 'fk_datamanagement_process_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
