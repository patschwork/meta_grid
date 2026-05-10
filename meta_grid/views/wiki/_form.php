		
<?php
// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Wiki */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

  $onChangeJs= <<<JS

    // console.log($(this).find('input:checked').val());
	if ($(this).find('input:checked').val() == "project") {
		// alert("project selected");
		document.getElementsByName("Wiki[fk_project_id]")[0].disabled=false;
		}
	else {
		document.getElementsByName("Wiki[fk_project_id]")[0].disabled=true;
	}
JS;
?>

<div class="wiki-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
	echo Html::radioList(
		'WikiMode'
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

	<?php
		echo $form->field($model, 'description')->widget(\floor12\summernote\Summernote::class, [
		'options' => ['id' => 'summernote-editor'],
		]);


		$this->registerJs(<<<'JS'
		var $el = $('#summernote-editor');
		if ($el.data('summernote')) { $el.summernote('destroy'); }
		$el.summernote({
			height: 300,
			toolbar: [
			['style', ['style']],
			['font', ['bold','italic','underline','clear']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul','ol','paragraph']],
			['insert', ['link','picture']],
			['view', ['fullscreen','codeview']],
			]
		});
		JS
		);
	?>




    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJS($onChangeJs);
?>
