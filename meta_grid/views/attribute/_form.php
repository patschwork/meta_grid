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

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// Codemirror
use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;
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

    <?php $form = ActiveForm::begin(); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
		echo $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'description') ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_object_persistence_method_id')->dropDownList($object_persistence_methodList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_persistence_method_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_datamanagement_process_id')->dropDownList($datamanagement_processList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_datamanagement_process_id')->textInput() ?>  -->


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
