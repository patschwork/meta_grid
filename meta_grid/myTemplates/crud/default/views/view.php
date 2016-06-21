<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
    	
    	
    	// Patrick, 2016-01-16, "related" Infos anzeigen
    	$setRelationInformation = 0;
    	if (substr($column->name,0,3)=="fk_")
    	{
    		$setRelationInformation = 1;
    		
    		if ($column->name=="fk_object_type_id") $setRelationInformation = 0;
    	
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
    			$genCode .= "             'value' => ";
    			$genCode .= "             		\$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->fkClient->name\n";
    			$genCode .= "            ],\n";
    		
    		}
    		
    		$nameFieldProperty = "name";
    		// Ausnahmen definieren
    		if ($column->name=="fk_tool_id") $nameFieldProperty = "tool_name";
    		if ($column->name=="fk_contact_group_id_as_data_owner") $relFieldname = "fkContactGroupIdAsDataOwner";
    		if ($column->name=="fk_contact_group_id_as_supporter") $relFieldname = "fkContactGroupIdAsSupporter";
    		
    		$genCode .= "            [\n";
    		$genCode .= "             'label' => '$relFieldnameLabel',\n";
    		$genCode .= "             'value' => ";
    		$genCode .= "             		\$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->$nameFieldProperty\n";
    		$genCode .= "            ],\n";

    	}
    	 
        $format = $generator->generateColumnFormat($column);

        // Formate für Splaten anpassen. z.B. gibt eine email Spalte
        $fieldFormat = "";
        if ($format != 'text')
        {
        	$fieldFormat = ":" . $format;
        	if ($column->name=="email") $fieldFormat = ":email";
        	if ($column->name=="description") $fieldFormat = ":html";
        }
        
        if ($setRelationInformation==1)
        {
        	echo $genCode;
        }
        else
        {
        	echo "            '" . $column->name . $fieldFormat . "',\n";
        }
    }
}
?>
        ],
    ]) ?>

    	<?php 
		echo "<?php\n";
		
		// Bugfix zu T19
		$commentTabGeneration=false;
		if ($generator->modelClass=="app\models\Client") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\Project") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\ToolType") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\DBTableType") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\Tool") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\DataDeliveryType") $commentTabGeneration=true;
		if ($generator->modelClass=="app\models\DataTransferType") $commentTabGeneration=true;
		
		if ($commentTabGeneration) 
		{
			echo "\t\t// bei Objekttyp 'client' oder 'project' keine Kommentierung oder Mapping...\n";
			echo "\t\t/*\n";
		}
		
		?>
		// {... Kommentierung pro Object
		// 		autogeneriert ueber gii/CRUD
		$objectcommentSearchModel = new app\models\ObjectcommentSearch();
        
        $query = app\models\Objectcomment::find();
        $objectcommentDataProvider = new ActiveDataProvider([
        		'query' => $query,
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $model->id,
        		'ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $model->id,
        		'filter_ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);

        
		echo Tabs::widget([
			'items' => 
			[
				[
					'label' => Yii::t('app', 'Comments'),
					'content' => $this->render('../objectcomment/_index_external', [
								    'searchModel' => $objectcommentSearchModel,
            						'dataProvider' => $objectcommentDataProvider,
								    ]),
					'active' => true
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapobject2object/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false
				],				
			],
		]);
		// Kommentierung pro Object ...}

	    <?php 
		// bei Objekttyp "client" keine Kommentierung oder Mapping...
		if ($commentTabGeneration) echo "*/\n";
		?>
		
	?>  
    
    
</div>
