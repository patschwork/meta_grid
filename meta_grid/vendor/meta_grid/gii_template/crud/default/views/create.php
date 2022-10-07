<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

<?php 
	// Patrick, 2016-01-15, #fk_Felder
	$routedVariables = "";
	$columnslist = $generator->getColumnNames();
	foreach ($columnslist as $column) {
		if (substr($column,0,3)=="fk_")
		{
			$fk_model_variable = str_replace("_id","",str_replace("fk_","",$column));
			$routedVariables .= "'$fk_model_variable"."List' => \$$fk_model_variable"."List,		// autogeneriert ueber gii/CRUD\n";
		}
	}
?>

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?php echo "Yii::t('app', 'Create {modelClass}', ['modelClass' => " . "Yii::t('app', '" . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . "')])"; ?>; 
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">

    <h3><?= "<?= " ?>Html::encode($this->title) ?></h3>

    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		<?= $routedVariables?>
		<?php if ($generator->modelClass === "app\models\DbTableField"): ?>
	'fk_db_table_id' => isset($fk_db_table_id) ? $fk_db_table_id : null,
		<?php endif; ?>
		<?php if ($generator->modelClass === "app\models\Bracket"): ?>
	'modelsBracketSearchPattern' => isset($modelsBracketSearchPattern) ? $modelsBracketSearchPattern : null,
		<?php endif; ?>
		'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
	]) ?>

</div>