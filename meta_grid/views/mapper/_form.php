		
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
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

    <?= $form->field($model, 'ref_fk_object_id_1')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_1')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_id_2')->textInput() ?>

    <?= $form->field($model, 'ref_fk_object_type_id_2')->textInput() ?>

	<?php
		// auto-generated via gii/CRUD
		Pjax::begin(['id'=>'id-pjax-map-object2-object-form_fk_mapping_qualifier_id', 'timeout' => false, 'enablePushState' => false]);
		echo $form->field($model, 'fk_mapping_qualifier_id', ['template' => '<label class="control-label">{label}</label><div class="input-group">{input}<span class="input-group-btn">'.Html::button('+', [
			'value' => Url::to('index.php?r=mappingqualifier/create&isfrommodal=true&modalparent=id-pjax-map-object2-object-form_fk_mapping_qualifier_id&refreshfield=name__fk_mapping_qualifier_id')
			, 'class' => 'btn btn-primary'
			, 'id' => 'modalButtonCreate__fk_mapping_qualifier_id'
			, 'hidden' => !Yii::$app->User->can('create-' . 'mappingqualifier')
		]).'</span></div>'])->dropDownList($mapping_qualifierList, ['id'=>'name__fk_mapping_qualifier_id']);
		Pjax::end();
 	?>
 <!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_mapping_qualifier_id')->textInput() ?>  -->



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
	$('#modalButtonCreate__fk_mapping_qualifier_id').click(function() {
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