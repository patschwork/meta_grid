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

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
		$ignore1 = "";
		$ignore2 = "";
		if (($attribute=="uuid") || (substr($attribute,0,3)=="fk_") || ($attribute=="description"))
		{
			$ignore1 = "<!--  	// automatisch auskommentiert ueber gii/CRUD";
			$ignore2 = "  -->";
		}
		
		$createDropDown = 0;
		if (substr($attribute,0,3)=="fk_") $createDropDown = 1;
		if ($attribute=="fk_object_type_id") $createDropDown = 0;
		
		if ($createDropDown == 1)
		{
			$fk_model_variable = str_replace("_id","",str_replace("fk_","",$attribute));
			$routedVariables = "\$$fk_model_variable"."List";
				
			
			echo "	<?php\n"; 
			echo "\t\t// autogeneriert ueber gii/CRUD\n";
			echo "\t".'	echo $form->field($model, \''.$attribute.'\')->dropDownList('.$routedVariables.', [\'id\'=>\'name\']);'."\n";
    		echo "	?>\n ";
		}
		
		$createHTMLEditor = 0;
		if ($attribute=="description") $createHTMLEditor = 1;

		if ($createHTMLEditor==1)
		{
			echo "	<?php\n"; 
			echo "		echo \$form->field(\$model, '$attribute')->widget(\\yii\\redactor\\widgets\\Redactor::className());";
			echo "	?>\n ";
		}

/*		echo $ignore1."    <?= " . $generator->generateActiveField($attribute) . " ?>" . $ignore2 . "\n\n";		*/
		echo $ignore1."    <?= " . generateActiveField($attribute, $generator) . " ?>" . $ignore2 . "\n\n";

    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
