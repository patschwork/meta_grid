<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContactSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uuid') ?>

    <?= $form->field($model, 'fk_object_type_id') ?>

    <?= $form->field($model, 'fk_contact_group_id') ?>

    <?= $form->field($model, 'fk_client_id') ?>

    <?= $form->field($model, 'givenname') ?>

    <?= $form->field($model, 'surname') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'mobile') ?>

    <?= $form->field($model, 'ldap_cn') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'fk_deleted_status_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
