<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Objectcomment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objectcomment-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'uuid')->textarea(['rows' => 6]) ?> -->

    <!-- <?= $form->field($model, 'fk_object_type_id')->textInput() ?> -->

    <?= $form->field($model, 'ref_fk_object_id')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at_datetime')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
