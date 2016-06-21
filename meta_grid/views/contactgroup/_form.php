<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContactGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-group-form">

    <?php $form = ActiveForm::begin(); ?>

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid')->textarea(['rows' => 6]) ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_client_id')->dropDownList($clientList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_client_id')->textInput() ?>  -->

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

	<?php
		$form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
     <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_name')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
