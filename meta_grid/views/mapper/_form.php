		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

    <?= $form->field($model, 'ref_fk_object_id_1')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_1')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_id_2')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_2')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
