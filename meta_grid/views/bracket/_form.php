		
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
use wbraganca\dynamicform\DynamicFormWidget; use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Bracket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bracket-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// auto-generated via gii/CRUD
		Pjax::begin(['id'=>'id-pjax-bracket-form_fk_project_id', 'timeout' => false, 'enablePushState' => false]);
		echo $form->field($model, 'fk_project_id', ['template' => '<label class="control-label">{label}</label><div class="input-group">{input}<span class="input-group-btn">'.Html::button('+', [
			'value' => Url::to('index.php?r=project/create&isfrommodal=true&modalparent=id-pjax-bracket-form_fk_project_id&refreshfield=name__fk_project_id')
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
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_attribute_id')->widget(\vendor\meta_grid\attribute_select\AttributeSelectWidget::className());
	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_attribute_id')->textInput() ?>  -->

	<?php
		// auto-generated via gii/CRUD
		Pjax::begin(['id'=>'id-pjax-bracket-form_fk_object_type_id_as_searchFilter', 'timeout' => false, 'enablePushState' => false]);
		echo $form->field($model, 'fk_object_type_id_as_searchFilter')->dropDownList($object_type_as_searchFilterList, ['id'=>'name', 'options' => [ $model->fk_object_type_id_as_searchFilter => ['selected ' => true]]]);
		Pjax::end();
 	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_object_type_id_as_searchFilter')->textInput() ?>  -->


	<div class="row">
	
		<div class="panel panel-default">
	        <div class="panel-heading"><h4><i class="glyphicon glyphicon-th-list"></i> SearchPattern</h4></div>
	        <div class="panel-body">
	             <?php DynamicFormWidget::begin([
	                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
	                'widgetBody' => '.container-items', // required: css class selector
	                'widgetItem' => '.item', // required: css class
	                // 'limit' => 4, // the maximum times, an element can be cloned (default 999)
	                'min' => 1, // 0 or 1 (default 1)
	                'insertButton' => '.add-item', // css class
	                'deleteButton' => '.remove-item', // css class
	                'model' => $modelsBracketSearchPattern[0],
	                'formId' => 'dynamic-form',
	                'formFields' => [
	                    'searchPattern',
	                ],
	            ]); ?>
	
	            <div class="container-items"><!-- widgetContainer -->
	            <?php foreach ($modelsBracketSearchPattern as $i => $modelBracketSearchPattern): ?>
	                <div class="item panel panel-default"><!-- widgetBody -->
	                    <div class="panel-heading">
	                        <h3 class="panel-title pull-left">SearchPattern</h3>
	                        <div class="pull-right">
	                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
	                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
	                        </div>
	                        <div class="clearfix"></div>
	                    </div>
	                    <div class="panel-body">
	                        <?php
	                            // necessary for update action.
	                            if (! $modelBracketSearchPattern->isNewRecord) {
	                                echo Html::activeHiddenInput($modelBracketSearchPattern, "[{$i}]fk_bracket_id");
	                            }
	                        ?>
	                        <?= $form->field($modelBracketSearchPattern, "[{$i}]searchPattern")->textInput(['maxlength' => true]) ?>
	                    </div>
	                </div>
	            <?php endforeach; ?>
	            </div>
	            <?php DynamicFormWidget::end(); ?>
	        </div>
	    </div>
	
	</div>

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
	$('#modalButtonCreate__fk_object_type_id_as_searchFilter').click(function() {
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