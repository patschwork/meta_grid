		
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
/* @var $model app\models\DbTableField */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="db-table-field-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// auto-generated via gii/CRUD
		Pjax::begin(['id'=>'id-pjax-db-table-field-form_fk_project_id', 'timeout' => false, 'enablePushState' => false]);
		echo $form->field($model, 'fk_project_id', ['template' => '<label class="control-label">{label}</label><div class="input-group">{input}<span class="input-group-btn">'.Html::button('+', [
			'value' => Url::to('index.php?r=project/create&isfrommodal=true&modalparent=id-pjax-db-table-field-form_fk_project_id&refreshfield=name__fk_project_id')
			, 'class' => 'btn btn-primary'
			, 'id' => 'modalButtonCreate__fk_project_id'
			, 'hidden' => !Yii::$app->User->can('create-' . 'project')
		]).'</span></div>'])->dropDownList($projectList, ['id'=>'name__fk_project_id']);
		Pjax::end();
 	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
		echo $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'description') ?>  -->

	<?php
		// auto-generated via gii/CRUD
	if (isset($fk_db_table_id))
	{
		echo $form->field($model, 'fk_db_table_id')->dropDownList($db_tableList, ['id'=>'name', 'options' => [ $fk_db_table_id => ['selected ' => true]]]);
	}
	else
	{
		echo $form->field($model, 'fk_db_table_id', ['template' => '<label class="control-label">{label}</label><div class="input-group">{input}<span class="input-group-btn">'.Html::button('+', [
			'value' => Url::to('index.php?r=dbtable/create&isfrommodal=true&modalparent=id-pjax-db-table-field-form_fk_db_table_id&refreshfield=name__fk_db_table_id')
			, 'class' => 'btn btn-primary'
			, 'id' => 'modalButtonCreate__fk_db_table_id'
			, 'hidden' => !Yii::$app->User->can('create-' . 'dbtable')
		]).'</span></div>'])->dropDownList($db_tableList, ['id'=>'name__fk_db_table_id']);
	}
	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_db_table_id')->textInput() ?>  -->

    <?= $form->field($model, 'datatype') ?>

    <?= $form->field($model, 'bulk_load_checksum') ?>

    <?= $form->field($model, 'is_PrimaryKey')->checkbox() ?>

    <?= $form->field($model, 'is_BusinessKey')->checkbox() ?>

    <?= $form->field($model, 'is_GDPR_relevant')->checkbox() ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php Modal::begin([
	'id'     => 'modalCreate',
	'size'   => 'modal-lg'
]);
echo "<div id='modalContent'>Nothing to show</div>";
Modal::end();

$script1 = <<< JS
$(function(){
	$('#modalButtonCreate__fk_project_id').click(function() {
		$('#modalCreate').modal('show')
		.find('#modalContent')
		.load($(this).attr('value'));
	});
	$('#modalButtonCreate__fk_db_table_id').click(function() {
		$('#modalCreate').modal('show')
		.find('#modalContent')
		.load($(this).attr('value'));
	});

	$('#modalCreate').on('hidden.bs.modal', function () {
			$('#modalCreate').find('#modalContent').html("Nothing to show");
	})
});
JS;
$this->registerJS($script1);


	?>

<?php $script2 = <<< JS
$('form#{$model->formName()}').on('beforeSubmit', function(e){
    var \$form = $(this);
    $.post(
        \$form.attr("action"), //serialize Yii2 form 
        \$form.serialize()
    )
    .done(function(result){
        result = JSON.parse(result);
        if(result.status == 'Success'){
            $(\$form).trigger("reset");
            $(document).find('#modalCreate').modal('hide');
            // reload with updated content (new entry)
            $.pjax.reload({container:'§§§_1'});
            $(document).on('ready pjax:success', function(){
                // auto-select created entry
                $(document).find("select#§§§_2").val(result.message).trigger("change");
            });
        }else{
            $(\$form).trigger("reset");
            $("#message").html(result.message);
        }
    })
    .fail(function(){
        console.log("server error");
    });
    return false;
});
JS;
$script2 = str_replace('§§§_1', '#'.$modalparent, $script2);
$script2 = str_replace('§§§_2', $refreshfield, $script2);
$this->registerJS($script2);
?>