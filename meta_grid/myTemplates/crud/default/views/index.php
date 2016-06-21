<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

<?php
		echo "<?php\n";
    	echo "// Das ist nicht der Yii2-Way, ... @ToDo\n";
    	echo 'if (isset($_GET["searchShow"]))'."\n";
    	echo "{"."\n";
    	echo '	echo $this->'."render('_search', ['model' =>".'$searchModel]);'."\n";
    	echo "}"."\n";
    	echo "else"."\n"; 
    	echo "{"."\n";
    	echo '	echo "<a' . " class='btn btn-default' href='index.php" . '?r=".$_GET["r"]."&' . "searchShow=1'>Advanced" . ' Search</a></br></br>";'."\n";
    	echo "}"."\n";
    	?>
?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
//         	echo "// ABC";
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        
        // Patrick, 2016-01-16, ueberspringen der Felder welche nicht angezeigt werden sollen.
        if ($column->name=="id") continue;
        if ($column->name=="uuid") continue;
        if ($column->name=="fk_object_type_id") continue;

        // Patrick, 2016-01-16, "related" Infos anzeigen
        $setRelationInformation = 0;
        if (substr($column->name,0,3)=="fk_")
        {
        	$setRelationInformation = 1;

        	// Der Name muss gewandelt werden, damit es zu dem Property im Model passt
        	// fk_tabellen_name -> fkTabellenName
        	$relFieldname = "";
        	$relFieldname = str_replace("_id","",$column->name);
        	$relFieldnameSplit = explode("_",$relFieldname);
        	foreach ($relFieldnameSplit as $rElement)
        	{
        		if ($rElement=="fk")
        		{
        			$relFieldname = $rElement;
        			continue;
        		}
        		$relFieldname .= strtoupper(substr($rElement,0,1)).substr($rElement,1,strlen($rElement)-1);
        	}
        	
        	$relFieldnameLabel = Inflector::camel2words(str_replace("fk_","",str_replace("_id","",$column->name)));

        	$genCode = "";
        	if ($column->name=="fk_project_id")
        	{
	        	$genCode .= "            [\n";
	        	$genCode .= "             'label' => 'Client',\n";
	        	$genCode .= "             'value' => function(\$model) {\n";
	        	$genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->fkClient->name;\n";
	        	$genCode .= "             		},\n";
	        	$genCode .= "            ],\n";
        	}

        	$nameFieldProperty = "name";
        	// Ausnahmen definieren
        	if ($column->name=="fk_tool_id") $nameFieldProperty = "tool_name";

        	$genCode .= "            [\n";
        	$genCode .= "             'label' => '$relFieldnameLabel',\n";
        	$genCode .= "             'value' => function(\$model) {\n";
        	// $genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->$nameFieldProperty;\n";
        	$genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : ".'(isset($_GET["searchShow"]) ? '."\$model->".$relFieldname."->$nameFieldProperty . ' [' . \$model->".$column->name .  " . ']'" . " : " . "\$model->".$relFieldname."->$nameFieldProperty)";
        	$genCode .= ";\n";
        	$genCode .= "             		},\n";
        	$genCode .= "            ],\n";
        
        }
        
		// Formate für Splaten anpassen. z.B. gibt eine email Spalte
        $fieldFormat = "";
        if ($format != 'text')
        {
        	$fieldFormat = ":" . $format;
        	if ($column->name=="email") $fieldFormat = ":email";
        	if ($column->name=="description") $fieldFormat = ":html";
        }
        
        if (++$count < 6) {
		
        	if ($setRelationInformation==1)
			{
				echo $genCode;
			}
        	else 
        	{
        		echo "            '" . $column->name . $fieldFormat . "',\n";
        	}
        	
        } else {
        	if ($setRelationInformation==1)
        	{
        		echo "/*";
        		echo $genCode;
        		echo "*/";
        	}
        	else
        	{
        		echo "            // '" . $column->name . $fieldFormat . "',\n";
        	}
        }
    }
}
?>

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
