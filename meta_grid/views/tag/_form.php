		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

  $onChangeJs= <<<JS

    // console.log($(this).find('input:checked').val());
	if ($(this).find('input:checked').val() == "project") {
		// alert("project selected");
		document.getElementsByName("Tag[fk_project_id]")[0].disabled=false;
		}
	else {
		document.getElementsByName("Tag[fk_project_id]")[0].disabled=true;
	}
JS;
?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
	echo Html::radioList(
		'TagMode'
		, $mode
		, [
			'global' => Yii::t('app', 'Global tag')
			, 'project' => Yii::t('app', 'Project tag')
			, 'personal' => Yii::t('app', 'Personal tag')]
			, ['onchange' => new \yii\web\JsExpression($onChangeJs)
			,
			'item' => function ($index, $label, $name, $checked, $value) {
				$RBACHelper = new \vendor\meta_grid\helper\RBACHelper();
				$disabled = false;
				if ($value == "project")
				{
					$disabled = !isset($RBACHelper->matrixRoleTag(Yii::$app->user->id)["create_or_edit"]["fk_project_id"]);
				}
				if ($value == "global")
				{
					$disabled = !isset($RBACHelper->matrixRoleTag(Yii::$app->user->id)["create_or_edit"]["GLOBAL"]);
				}
				return Html::radio($name, $checked, [
					'value' => $value,
					'label' => Html::encode($label),
					'disabled' => $disabled,
					]);
				},
			]
		);
	?>

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name', 'options' => $projectListDisables]);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		// echo $form->field($model, 'fk_user_id')->dropDownList($userList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_user_id')->textInput() ?>  -->



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJS($onChangeJs);
?>