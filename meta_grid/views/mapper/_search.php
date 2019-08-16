<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2ObjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'ref_fk_object_id_1') ?>

    <?= $form->field($model, 'ref_fk_object_type_id_1') ?>

    <?= $form->field($model, 'ref_fk_object_id_2') ?>

    <?php // echo $form->field($model, 'ref_fk_object_type_id_2') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
