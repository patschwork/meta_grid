<style>
.bg {
	background:#CCC;
}
.mgObjectHighlight
{
	/*border: solid 1px blue;*/
	background: #7FC9FF;

}
</style>
		
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
// Codemirror
use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Attribute */
/* @var $form yii\widgets\ActiveForm */
?>
<script type="text/javascript">

function getBaseURL()
{
    return "<?= Yii::$app->urlManager->createUrl ( 'mapobject2object' )."/" ?>";
}

var startChar_SQL_Filter = '###';
var p_scope_field		 = '#fk_project_id :selected';

</script> 

<div class="attribute-form">

    <?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>
<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// auto commented via gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// auto-generated via gii/CRUD
		Pjax::begin(['id'=>'id-pjax-attribute-form_fk_project_id', 'timeout' => false, 'enablePushState' => false]);
		echo $form->field($model, 'fk_project_id', ['template' => '<label class="control-label">{label}</label><div class="input-group">{input}<span class="input-group-btn">'.Html::button('+', [
			'value' => Url::to('index.php?r=project/create&isfrommodal=true&modalparent=id-pjax-attribute-form_fk_project_id&refreshfield=name__fk_project_id')
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
		// eigene Autocomplete: http://stackoverflow.com/questions/19244449/codemirror-autocomplete-custom-list
		// evtl. auch ein Hinweis: https://github.com/samdark/codemirror-autosuggest
		echo $form->field($model, 'formula')->widget(
		    CodemirrorWidget::className(),
		    [
		        'assets'=>
		    		[
		        		CodemirrorAsset::THEME_BASE16_LIGHT,
			            CODEMIRRORASSET::ADDON_EDIT_MATCHBRACKETS,
		        		CODEMIRRORASSET::ADDON_SELECTION_ACTIVE_LINE,
		        		CODEMIRRORASSET::MODE_SQL,
		        		CodemirrorAsset::ADDON_HINT_SHOW_HINT,
	        			CodemirrorAsset::ADDON_HINT_SQL_HINT,
		    	        
		    	        CodemirrorAsset::ADDON_AUTOSUGGEST_MODIFIED,
		    	        
		        	],
		        'settings'=>
		    		[
		            	'lineNumbers' => true,
		            	'mode' => 'text/x-mssql',
		        		'theme' => 'base16-light',
						'styleActiveLine' => true,
	        			'matchBrackets' => true,
	        			'extraKeys' => 	[
	        								"Ctrl-Space" => "autocomplete",
	        							],

		    				'autoSuggest' => "js:[
			    				{
									mode: 'sql',
									startChar: '+++',
									listCallback: function(){
										return getDataViaAjax()
										}
								},
			    				{
									mode: 'sql',
									startChar: startChar_SQL_Filter,
									listCallback: function(filter, thisStartChar){
										return getDataViaAjaxByFilter(filter, thisStartChar)
										}
								}
		    				]"
		    		]
		    	]
		);
    ?>
    
    
	<?php
    
	// Initiale Markierungen durchfuehren
	echo $this->registerJs(
			"
				var cm = $('.CodeMirror')[0].CodeMirror;
				doMarkerOverlay(cm);
			",
			yii\web\View::POS_READY,
			null
	);
	
    ?>


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