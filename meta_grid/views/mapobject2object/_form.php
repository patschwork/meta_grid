<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'uuid')->textarea(['rows' => 6]) ?> -->

    <?= $form->field($model, 'ref_fk_object_id_1')->textInput() ?>

    <!-- <?= $form->field($model, 'ref_fk_object_type_id_1')->textInput() ?> -->
    <?php 
		echo $form->field($model, 'ref_fk_object_type_id_1')->dropDownList($objectTypesList, ['id'=>'name']);
    ?>
    
    <?= $form->field($model, 'ref_fk_object_id_2')->textInput() ?>

    <!-- <?= $form->field($model, 'ref_fk_object_type_id_2')->textInput() ?> -->
    <?php 
		echo $form->field($model, 'ref_fk_object_type_id_2')->dropDownList($objectTypesList, ['id'=>'name']);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
