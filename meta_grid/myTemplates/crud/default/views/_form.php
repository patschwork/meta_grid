<?php if ($generator->modelClass === "app\models\Attribute"): ?>
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
<?php endif; ?>		
<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

	// Patrick, 2016-02-06
	// Ableitung der Funktion, da ueber SQLite bei Datentyp TEXT eine Textarea erzeugt
	function generateActiveField($attribute, $gen)
	{
		$tableSchema = $gen->getTableSchema();
		if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
			if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
				return "\$form->field(\$model, '$attribute')->passwordInput()";
			} else {
			return "\$form->field(\$model, '$attribute')";
			}
			}
			$column = $tableSchema->columns[$attribute];
			if ($column->phpType === 'boolean') {
				return "\$form->field(\$model, '$attribute')->checkbox()";
			} elseif ($column->type === 'text') {
				return "\$form->field(\$model, '$attribute')";
					} else {
					if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
					$input = 'passwordInput';
					} else {
					$input = 'textInput';
	            }
			if (is_array($column->enumValues) && count($column->enumValues) > 0) {
			$dropDownOptions = [];
				foreach ($column->enumValues as $enumValue) {
				$dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
				}
				return "\$form->field(\$model, '$attribute')->dropDownList("
					. preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";
					} elseif ($column->phpType !== 'string' || $column->size === null) {
					return "\$form->field(\$model, '$attribute')->$input()";
					} else {
					return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
					}
					}
	    }


/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
<?= $generator->modelClass=="app\models\Sourcesystem" ? "use vendor\meta_grid\contactgroup_select\ContactGroupSelectWidget;" : "" ?>
<?= $generator->modelClass=="app\models\Attribute" ? "// Codemirror\nuse conquer\codemirror\CodemirrorWidget;\nuse conquer\codemirror\CodemirrorAsset;" : "" ?>
<?= $generator->modelClass=="app\models\Bracket" ? "use wbraganca\dynamicform\DynamicFormWidget; " : "" ?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if ($generator->modelClass === "app\models\Attribute"): ?>
<script type="text/javascript">

function getBaseURL()
{
    return "<?php echo '<?= Yii::$app->urlManager->createUrl ( \'mapobject2object\' )."/" ?>'; ?>";
}

var startChar_SQL_Filter = '###';
var p_scope_field		 = '#fk_project_id :selected';

</script> 
<?php endif; ?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?><?= $generator->modelClass=="app\models\Bracket" ? "\$form = ActiveForm::begin(['id' => 'dynamic-form']); ?>" : "\$form = ActiveForm::begin(); ?>" ?>

<?php foreach ($generator->getColumnNames() as $attribute) {

	// Patrick, ignore fields
	if ($attribute=="fk_deleted_status_id") continue; // not for now (T204)
	if ($attribute=="fk_datamanagement_process_id") continue; // not for now (T204)
	if ($attribute=="fk_object_persistence_method_id") continue; // not for now (T204)
	if ($attribute=="source_definition") continue; // not for now (T204)
	if ($attribute=="source_comment") continue; // not for now (T204)
	if ($attribute=="source_definition_language") continue; // not for now (T204)

    if (in_array($attribute, $safeAttributes)) {
		$ignore1 = "";
		$ignore2 = "";
		if (($attribute=="uuid") || (substr($attribute,0,3)=="fk_") || ($attribute=="description") || ($attribute=="comment"))
		{
			$ignore1 = "<!--  	// automatisch auskommentiert ueber gii/CRUD";
			$ignore2 = "  -->";
		}
		
		$createDropDown = 0;
		$createContactGroupSelectWidget = 0;
		$createAttributeSelectWidget = 0;
		if (substr($attribute,0,3)=="fk_") $createDropDown = 1;
		if ($attribute=="fk_object_type_id") $createDropDown = 0;


		if (explode("_as_",$attribute)[0] === "fk_contact_group_id")
		{
            $createDropDown = 0;
            $createContactGroupSelectWidget = 1;
        }

		
		if ($attribute=="fk_attribute_id")
		{
            $createDropDown = 0;
            $createAttributeSelectWidget = 1;
        }
		
		
		if ($createDropDown == 1)
		{
			$fk_model_variable = str_replace("_id","",str_replace("fk_","",$attribute));
			$routedVariables = "\$$fk_model_variable"."List";
				
			$dropDown1 = "	<?php\n"; 
			$dropDown2 = "\t\t// autogeneriert ueber gii/CRUD\n";
			$dropDown3 = "\t".'	echo $form->field($model, \''.$attribute.'\')->dropDownList('.$routedVariables.', [\'id\'=>\'name\']);'."\n";
    		$dropDown4 = "	?>\n ";
			if ($generator->modelClass === "app\models\DbTableField" && $attribute=="fk_db_table_id")
			{
				echo $dropDown1;
				echo $dropDown2;
				echo "\t" . "if (isset(\$fk_db_table_id))" . "\n";
				echo "\t" . "{" . "\n";
				echo "\t\t" . "echo \$form->field(\$model, 'fk_db_table_id')->dropDownList(\$db_tableList, ['id'=>'name', 'options' => [ \$fk_db_table_id => ['selected ' => true]]]);" . "\n";
				echo "\t" . "}" . "\n";
				echo "\t" . "else" . "\n";
				echo "\t" . "{" . "\n";
				echo $dropDown3;
				echo "\t" . "}" . "\n";
				echo $dropDown4;
			}
			else
			{
				if ($generator->modelClass === "app\models\Bracket" && $attribute=="fk_object_type_id_as_searchFilter")
				{
					echo $dropDown1;
					echo $dropDown2;
					// echo "\t" . "if (!is_null(\$model->$attribute))" . "\n";
					// echo "\t" . "{" . "\n";
					echo "\t\t" . "echo \$form->field(\$model, '$attribute')->dropDownList($routedVariables, ['id'=>'name', 'options' => [ \$model->$attribute => ['selected ' => true]]]);" . "\n";
					// echo "\t" . "}" . "\n";
					// echo "\t" . "else" . "\n";
					// echo "\t" . "{" . "\n";
					// echo $dropDown3;
					// echo "\t" . "}" . "\n";
					echo $dropDown4;
				}
				else
				{
					echo $dropDown1;
					echo $dropDown2;
					echo $dropDown3;
					echo $dropDown4;
				}
			}
		}

		if ($createContactGroupSelectWidget == 1)
		{
			echo "	<?php\n"; 
			echo "\t\t// autogeneriert ueber gii/CRUD\n";
			echo "\t".'	echo $form->field($model, \''.$attribute.'\')->widget(\vendor\meta_grid\contactgroup_select\ContactGroupSelectWidget::className());'."\n";
			echo "	?>\n ";
		}
		
		if ($createAttributeSelectWidget == 1)
		{
			echo "	<?php\n"; 
			echo "\t\t// autogeneriert ueber gii/CRUD\n";
			echo "\t".'	echo $form->field($model, \''.$attribute.'\')->widget(\vendor\meta_grid\attribute_select\AttributeSelectWidget::className());'."\n";
			echo "	?>\n ";
		}
		
		$createHTMLEditor = 0;
		if ($attribute=="description") $createHTMLEditor = 1;
		if ($attribute=="comment") $createHTMLEditor = 1;

		if ($createHTMLEditor==1)
		{
			echo "	<?php\n"; 
			echo "		echo \$form->field(\$model, '$attribute')->widget(\\yii\\redactor\\widgets\\Redactor::className());";
			echo "	?>\n ";
		}

/*		echo $ignore1."    <?= " . $generator->generateActiveField($attribute) . " ?>" . $ignore2 . "\n\n";		*/
		if (($generator->modelClass === "app\models\Attribute") && ($attribute === "formula"))
		{
			echo "";
		}
		else
		{
			echo $ignore1."    <?= " . generateActiveField($attribute, $generator) . " ?>" . $ignore2 . "\n\n";			
		}

    }
} ?>
<?php if ($generator->modelClass === "app\models\Attribute"): ?>

	<?php echo "<?php\n"; ?>
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
    <?php echo "?>\n"; ?>
    
    
	<?php echo "<?php\n"; ?>
    
	// Initiale Markierungen durchfuehren
	echo $this->registerJs(
			"
				var cm = $('.CodeMirror')[0].CodeMirror;
				doMarkerOverlay(cm);
			",
			yii\web\View::POS_READY,
			null
	);
	
    <?php echo "?>\n"; ?>
<?php endif; ?>

<?php 
	if ($generator->modelClass === "app\models\Bracket")
	{
echo '	<div class="row">' . "\n";
echo '	' . "\n";
echo '		<div class="panel panel-default">' . "\n";
echo '	        <div class="panel-heading"><h4><i class="glyphicon glyphicon-th-list"></i> SearchPattern</h4></div>' . "\n";
echo '	        <div class="panel-body">' . "\n";
echo '	             <?php DynamicFormWidget::begin([' . "\n";
echo '	                \'widgetContainer\' => \'dynamicform_wrapper\', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]' . "\n";
echo '	                \'widgetBody\' => \'.container-items\', // required: css class selector' . "\n";
echo '	                \'widgetItem\' => \'.item\', // required: css class' . "\n";
echo '	                // \'limit\' => 4, // the maximum times, an element can be cloned (default 999)' . "\n";
echo '	                \'min\' => 1, // 0 or 1 (default 1)' . "\n";
echo '	                \'insertButton\' => \'.add-item\', // css class' . "\n";
echo '	                \'deleteButton\' => \'.remove-item\', // css class' . "\n";
echo '	                \'model\' => $modelsBracketSearchPattern[0],' . "\n";
echo '	                \'formId\' => \'dynamic-form\',' . "\n";
echo '	                \'formFields\' => [' . "\n";
echo '	                    \'searchPattern\',' . "\n";
echo '	                ],' . "\n";
echo '	            ]); ?>' . "\n";
echo '	' . "\n";
echo '	            <div class="container-items"><!-- widgetContainer -->' . "\n";
echo '	            <?php foreach ($modelsBracketSearchPattern as $i => $modelBracketSearchPattern): ?>' . "\n";
echo '	                <div class="item panel panel-default"><!-- widgetBody -->' . "\n";
echo '	                    <div class="panel-heading">' . "\n";
echo '	                        <h3 class="panel-title pull-left">SearchPattern</h3>' . "\n";
echo '	                        <div class="pull-right">' . "\n";
echo '	                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>' . "\n";
echo '	                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>' . "\n";
echo '	                        </div>' . "\n";
echo '	                        <div class="clearfix"></div>' . "\n";
echo '	                    </div>' . "\n";
echo '	                    <div class="panel-body">' . "\n";
echo '	                        <?php' . "\n";
echo '	                            // necessary for update action.' . "\n";
echo '	                            if (! $modelBracketSearchPattern->isNewRecord) {' . "\n";
echo '	                                echo Html::activeHiddenInput($modelBracketSearchPattern, "[{$i}]fk_bracket_id");' . "\n";
echo '	                            }' . "\n";
echo '	                        ?>' . "\n";
echo '	                        <?= $form->field($modelBracketSearchPattern, "[{$i}]searchPattern")->textInput([\'maxlength\' => true]) ?>' . "\n";
echo '	                    </div>' . "\n";
echo '	                </div>' . "\n";
echo '	            <?php endforeach; ?>' . "\n";
echo '	            </div>' . "\n";
echo '	            <?php DynamicFormWidget::end(); ?>' . "\n";
echo '	        </div>' . "\n";
echo '	    </div>' . "\n";
echo '	' . "\n";
echo '	</div>' . "\n";
		
	}
?>

    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>
</div>
