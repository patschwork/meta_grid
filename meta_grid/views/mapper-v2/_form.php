		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VAllObjectsUnion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vall-objects-union-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id')->textInput() ?>

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'object_type_name') ?>

    <?= $form->field($model, 'listvalue_1')->textInput() ?>

    <?= $form->field($model, 'listvalue_2')->textInput() ?>

    <?= $form->field($model, 'listkey')->textInput() ?>

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_client_id')->dropDownList($clientList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_client_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
