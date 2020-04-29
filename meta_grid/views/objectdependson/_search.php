<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ObjectDependsOnSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-depends-on-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'ref_fk_object_id_parent') ?>

    <?= $form->field($model, 'ref_fk_object_type_id_parent') ?>

    <?= $form->field($model, 'ref_fk_object_id_child') ?>

    <?= $form->field($model, 'ref_fk_object_type_id_child') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
