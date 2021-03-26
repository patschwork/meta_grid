<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
<?php if($generator->modelClass === "app\models\Attribute"): ?>

pre {
    white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
<?php endif;?>
</style>

<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

$searchModelClassViewInterfaceFullpath = str_replace('app\models\\', 'app\models\V',  ltrim($generator->searchModelClass, '\\')) . "interface";
$searchModelClassViewInterface = "V" . str_replace('app\models\\', '',  ltrim($generator->searchModelClass, '\\')) . "interface";

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use vendor\meta_grid\helper\RBACHelper;
use yii\helpers\Url;
<?= array_key_exists("fk_project_id", $generator->getTableSchema()->columns) ? "use $searchModelClassViewInterfaceFullpath;" : "" ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
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
    	echo '	echo "<a' . " class='btn btn-default' href='index.php" . '?r=".$_GET["r"]."&' . "searchShow=1'>\".Yii::t('app', 'Advanced Search').\"" . '</a></br></br>";'."\n";
    	echo "}"."\n";
    	?>
?>

    <p>
		<?= "<?= Yii::\$app->user->identity->isAdmin || Yii::\$app->User->can('create-" . str_replace("controller", "", strtolower ( StringHelper::basename($generator->controllerClass) ) ) . "')  ? " ?>Html::a(
		<?php echo "Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', '" . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . "'),])"; ?>, ['create'], ['class' => 'btn btn-success']) : "" ?>
		<?= ($generator->modelClass === "app\models\DbTable" || ($generator->modelClass === "app\models\DbTableField")) ? "<?php" : ""  ?>
		<?php if ($generator->modelClass === "app\models\DbTable" || ($generator->modelClass === "app\models\DbTableField")): ?>
		$request = Yii::$app->request;
		$queryString = $request->queryString;
		$controllerid = Yii::$app->controller->id;
		$destAction4CSVExport = "r=$controllerid/export_csv";
		$queryString4Export = str_replace("r=$controllerid", $destAction4CSVExport, $queryString);
		if (stristr($queryString, "r=$controllerid/index") || stristr($queryString, "r=$controllerid%2Findex"))
		{
			$queryString4Export = str_replace("r=$controllerid/index", $destAction4CSVExport, $queryString);
			$queryString4Export = str_replace("r=$controllerid%2Findex", $destAction4CSVExport, $queryString);
		}
		echo "<a class='btn btn-primary' href='index.php?$queryString4Export'>".Yii::t('app', 'Export as CSV')."</a></br></br>";
		<?php endif; ?>
<?= ($generator->modelClass === "app\models\DbTable" || ($generator->modelClass === "app\models\DbTableField")) ? "?>" : ""  ?>
	</p>

	<?php echo "<?php"; ?>

	$session = Yii::$app->session;
	
	// Inform user about set perspective_filter
	if (array_key_exists("fk_object_type_id",  $searchModel->attributes) === true && (isset($searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === true))
	{
		$fk_object_type_id=$searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;
		if ($session->hasFlash('perspective_filter_for_' . $fk_object_type_id))
		{	
			echo yii\bootstrap\Alert::widget([
					'options' => [
									'class' => 'alert-info',
					],
					'body' => $session->getFlash('perspective_filter_for_' . $fk_object_type_id),
			]);
		}		
	}
	
	if ($session->hasFlash('deleteError'))
	{	
		echo yii\bootstrap\Alert::widget([
				'options' => [
					'class' => 'alert alert-danger alert-dismissable',
				],
				'body' => $session->getFlash('deleteError'),
		]);
	}

	Url::remember();
	<?php echo "?>"; ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
	<?php //echo "<div class=\"table-responsive\">\n"; ?>
    <?= "<?= " ?>GridView::widget([
		'tableOptions' => ['id' => 'grid-view-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>', 'class' => 'table table-striped table-bordered'],
		'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
		],
		'layout' => "{pager}\n{summary}{items}\n{pager}",
       	'rowOptions' => function ($model, $key, $index, $grid) {
       		$controller = Yii::$app->controller->id;
       		return [
       				'ondblclick' => 'location.href="'
       				. Yii::$app->urlManager->createUrl([$controller . '/view','id'=>$key])
       				. '"',
       		];
       	},
		'options' => [
			'class' => 'thead_white',
		],    
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            <?php
            	if ($generator->modelClass === "app\models\Project")
            	{
            		echo ",\n";
            		// echo "\t\t\t\t" . "'template' => '{view} {update} {delete} {documentation}'," . "\n";
            		echo "\t\t\t\t" . "'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete} {documentation}')," . "\n";
            		echo "\t\t\t\t" . "'buttons' => [" . "\n";
            		echo "\t\t\t\t" . "		'documentation' => function (" . '$url, $model' . ") {" . "\n";
            		echo "\t\t\t\t" . "			return Html::a('<span class=\"glyphicon glyphicon-list-alt\"></span>', " . '$url' . ", [" . "\n";
            		echo "\t\t\t\t" . "					'title' => Yii::t('app', 'Documentation')," . "\n";
            		echo "\t\t\t\t" . "			]);" . "\n";
            		echo "\t\t\t\t" . "		}" . "\n";
            		echo "\t\t\t\t" . "]," . "\n";
            		echo "\t\t\t\t" . "'urlCreator' => function (" . '$action, $model, $key, $index' . ") {" . "\n";
            		echo "\t\t\t\t" . '	$controller = Yii::$app->controller->id;' . "\n";
            		echo "\t\t\t\t" . "" . "\n";
            		echo "\t\t\t\t" . "	if (" . '$action' . " === 'view') {" . "\n";
            		echo "\t\t\t\t" . '    	return Yii::$app->urlManager->createUrl([$controller' . " . '/' . " . '$action' . " ,'id'=>" . '$key' . "]);" . "\n";
            		echo "\t\t\t\t" . "    }" . "\n";
            		echo "\t\t\t\t" . "    if (" . '$action' . " === 'update') {" . "\n";
            		echo "\t\t\t\t" . "    	return Yii::" . '$app->urlManager->createUrl([$controller' . " . '/' . " . '$action ,' . "'id'=>" . '$key' . "]);" . "\n";
            		echo "\t\t\t\t" . "    }" . "\n";
            		echo "\t\t\t\t" . '    if ($action === ' . "'delete') {" . "\n";
            		echo "\t\t\t\t" . '    	return Yii::$app->urlManager->createUrl([$controller . ' . "'/' . " . '$action ,' . "'id'=>" . '$key' . "]);" . "\n";
            		echo "\t\t\t\t" . "    }" . "\n";
            		echo "\t\t\t\t" . '	if ($action === \'documentation\') {' . "\n";
            		echo "\t\t\t\t" . '		$url = "?r=documentation/createdocumentation&project_id=" . $key; ' . "\n";
            		echo "\t\t\t\t" . '		return $url;' . "\n";
            		echo "\t\t\t\t" . "	}" . "\n";
            		echo "\t\t\t\t" . "}" . "\n";
				}
				else
				{
					$update_dbtablefield_individual = "";
					$dbtablefield_additional = "";
					if ($generator->modelClass === "app\models\DbTableField")
					{
						$update_dbtablefield_individual = "{update-dbtablefield-individual} ";
						$dbtablefield_additional = '
						\'buttons\' => [
							\'update-dbtablefield-individual\' => function ($url, $model) {
		
								$html_btn = Html::a(\'<span style="color: silver;" class="glyphicon glyphicon-pencil"></span>\', $url, [
										\'title\' => Yii::t(\'app\', \'Update dbtablefield individual\'),
								]);
		
								$db_table_show_buttons_for_different_object_type_updates = \vendor\meta_grid\helper\Utils::get_app_config("db_table_show_buttons_for_different_object_type_updates");
								if ($db_table_show_buttons_for_different_object_type_updates == 1) 
								{
									return $html_btn;
								}
							}
						],
						\'urlCreator\' => function ($action, $model, $key, $index) {
							if ($action === \'update\') {
								$url = "?r=dbtablefieldmultipleedit/update&id=".$model->fk_db_table_id."#".$model->id; // your own url generation logic
								return $url;
							}
							if ($action === \'update-dbtablefield-individual\') {
								$url = "?r=dbtablefield/update&id=".$model->id; // your own url generation logic
								return $url;
							}
							// general button actions
							$controller = Yii::$app->controller->id;
							return Yii::$app->urlManager->createUrl([$controller . \'/\' . $action ,\'id\'=>$key]);
						}
						';
					} 
            		echo ",\n";
					echo "\t\t\t\t" . "'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} $update_dbtablefield_individual{delete}')," . "\n";
					if ($dbtablefield_additional !== "")  echo "\t\t\t\t" . $dbtablefield_additional;
				}            
            ?>
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
//     	if ($column->name === "fk_db_table_context_id") {
	    	// echo "<pre>";
	    	// var_dump($generator->modelClass);
	    	// echo "</pre>";
	    	// exit;
//     	}
        $format = $generator->generateColumnFormat($column);
        
        // Patrick, 2016-01-16, ueberspringen der Felder welche nicht angezeigt werden sollen.
        if ($column->name=="id") continue;
        if ($column->name=="uuid") continue;
        if ($column->name=="fk_object_type_id") continue;
        if ($column->name=="location") continue;
        if ($column->name=="fk_deleted_status_id") continue; // not for now
		
        if ($column->name=="description" && $generator->modelClass=="app\models\DbTable") continue; // phabricator-task: T23

        // Patrick, 2016-01-16, "related" Infos anzeigen
        $setRelationInformation = 0;
		$useGenCode = 0;
        if (substr($column->name,0,3)=="fk_")
        {
        	$setRelationInformation = 1;

        	// Der Name muss gewandelt werden, damit es zu dem Property im Model passt
        	// fk_tabellen_name -> fkTabellenName
        	$relFieldname = "";
        	$relModelClassName = "";
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
        		$relModelClassName .= strtoupper(substr($rElement,0,1)).substr($rElement,1,strlen($rElement)-1); 
        	}

//         	if ($column->name === "fk_db_table_context_id") {
//         		echo "<pre>";
//         		var_dump($relModelClassName);
//         		echo "</pre>";
//         		exit;
//         	}        	
        	
        	
        	$relFieldnameLabel = Inflector::camel2words(str_replace("fk_","",str_replace("_id","",$column->name)));
			
        	$multiple = ", 'multiple' => true";
        	$genCode = "";
        	if ($column->name=="fk_project_id")
        	{
	        	$genCode .= "            [\n";
	        	$genCode .= "             'label' => Yii::t('app', 'Client'),\n";
	        	$genCode .= "             'value' => function(\$model) {\n";
	        	$genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->fkClient->name;\n";
	        	$genCode .= "             		},\n";
	        	
	        	$genCode .= "             		'filter' => Select2::widget([" . "\n";
	        	$genCode .= "             				'model' => " . '$searchModel' . "," . "\n";
	        	// $genCode .= "             				'attribute' => 'fk_project_id'," . "\n";
	        	$genCode .= "             				'attribute' => 'fk_client_id'," . "\n";
	        	// $genCode .= "             				'data' => ArrayHelper::map(Project::find()->select('project.id, client.name, project.fk_client_id')->distinct()->joinWith('fkClient')->asArray()->all(), 'id', 'name')," . "\n";
	        	$genCode .= "             				'data' => ArrayHelper::map($searchModelClassViewInterface::find()->select(['fk_client_id', 'client_name'])->distinct()->asArray()->all(), 'fk_client_id', 'client_name')," . "\n";
	        	$genCode .= "             				'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_client_id']," . "\n";
	        	$genCode .= "             				'pluginOptions' => [" . "\n";
	        	$genCode .= "             						'allowClear' => true" . "\n";
	        	$genCode .= "             				]," . "\n";
	        	$genCode .= "             		])," . "\n";
	        	
	        	$genCode .= "            ],\n";
				
				$multiple = "";
        	}
			
			
        	$nameFieldProperty = "name";
        	// Ausnahmen definieren
        	if ($column->name=="fk_tool_id") $nameFieldProperty = "tool_name";

            if ($column->name=="fk_contact_group_id_as_supporter")
            {
                $relFieldname="fkContactGroupIdAsSupporter";
				$relModelClassName="ContactGroup";
            }
            if ($column->name=="fk_contact_group_id_as_data_owner")
            {
                $relFieldname="fkContactGroupIdAsDataOwner";
				$relModelClassName="ContactGroup";
            }
            if ($column->name=="fk_object_type_id_as_searchFilter")
            {
                $relFieldname="fkObjectTypeIdAsSearchFilter";
				$relModelClassName="ObjectType";
            }

        	$genCode .= "            [\n";
        	$genCode .= "             'label' => Yii::t('app', '$relFieldnameLabel'),\n";
        	$genCode .= "             'value' => function(\$model) {\n";
        	// $genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->$nameFieldProperty;\n";
        	$genCode .= "             		return \$model->".$column->name." == \"\" ? \$model->".$column->name." : ".'(isset($_GET["searchShow"]) ? '."\$model->".$relFieldname."->$nameFieldProperty . ' [' . \$model->".$column->name .  " . ']'" . " : " . "\$model->".$relFieldname."->$nameFieldProperty)";
        	$genCode .= ";\n";
        	$genCode .= "             		},\n";
        	
			$genCode .= "            'filter' => Select2::widget([" . "\n";
			$genCode .= "            		'model' => " . '$searchModel' . "," . "\n";
			$genCode .= "            		'attribute' => '" . $column->name . "'," . "\n";
			$genCode .= $column->name=="fk_project_id" ? "            		'data' => ArrayHelper::map($searchModelClassViewInterface::find()->select(['fk_project_id', 'project_name'])->distinct()->asArray()->all(), 'fk_project_id', 'project_name')," . "\n" : "            		'data' => ArrayHelper::map(app\\models\\" . $relModelClassName . "::find()->asArray()->all(), 'id', '$nameFieldProperty')," . "\n";
			// $genCode .= "            		'data' => ArrayHelper::map(app\\models\\" . $relModelClassName . "::find()->asArray()->all(), 'id', '$nameFieldProperty')," . "\n";
			$genCode .= "            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_" . $relFieldname . "'$multiple]," . "\n";
			$genCode .= "            		'pluginOptions' => [" . "\n";
			$genCode .= "            				'allowClear' => true" . "\n";
			$genCode .= "            		]," . "\n";
			$genCode .= "			])," . "\n";        		

// Als Vorbereitung
// 			$genCode .= "             'contentOptions' => function(" . '$data) {' . "\n";
// 			$genCode .= "             	return [" . "\n";
// 			$genCode .= "             			'class' => 'cell-with-tooltip'," . "\n";
// 			$genCode .= "             			'data-toggle' => 'tooltip'," . "\n";
// 			$genCode .= "             			'data-placement' => 'bottom'," . "\n"; // top, bottom, left, right
// 			$genCode .= "             			'data-container' => 'body'," . "\n"; // to prevent breaking table on hover
// 			$genCode .= "             			'title' => " . '$data->' . $column->name . "," . "\n";
// 			$genCode .= "             	];" . "\n";
// 			$genCode .= "             }" . "\n";
        	        		
        	$genCode .= "            ],\n";
        
        }

		if ($column->name=="formula" && $generator->modelClass=="app\models\Attribute")
		{
			$useGenCode = 1;
			$genCode = "";
			$genCode .= "            [\n";
			$genCode .= "             'label' => Yii::t('app', 'Formula'),\n";
			$genCode .= "             'value' => function(\$model) {\n";
			$genCode .= "             		return \$model->".$column->name." == \"\" ? NULL : \"<pre>\" . \$model->formulaWithLinks . \"</pre>\";\n";
			$genCode .= "             		},\n";
			$genCode .= "             'format' => 'html'\n";
			$genCode .= "            ],\n";
		}
        
		// Formate für Splaten anpassen. z.B. gibt eine email Spalte
        $fieldFormat = "";
        if ($format != 'text')
        {
			$fieldFormat = ":" . $format;
        	if ($column->name=="email") $fieldFormat = ":email";
        	if ($column->name=="description") $fieldFormat = ":html";
        	if ($column->name=="comment") $fieldFormat = ":html";
        }
        
        // { ... phabricator-task: T23
			if ($column->name=="name" && ($generator->modelClass=="app\models\DbTable" || $generator->modelClass=="app\models\DbTableField"))
			{
				$useGenCode = 1;
				$genCode = "";
				$genCode .= "            [\n";
				$genCode .= "             'label' => Yii::t('app', 'Database'),\n";
				$genCode .= "             'attribute' => 'databaseInfoFromLocation',\n";
				$genCode .= "            'filter' => Select2::widget([\n";
				$genCode .= "            		'model' => \$searchModel,\n";
				$genCode .= "            		'attribute' => 'databaseInfoFromLocation',\n";
				$genCode .= "            		'data' => ArrayHelper::map(app\models\VDbTable" . ($generator->modelClass=="app\models\DbTableField" ? "Field" : "") . "Searchinterface::find()->asArray()->all(), 'databaseInfoFromLocation', 'databaseInfoFromLocation'),\n";
				$genCode .= "            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_databaseInfoFromLocation'],\n";
				$genCode .= "            		'pluginOptions' => [\n";
				$genCode .= "            				'allowClear' => true\n";
				$genCode .= "            		],\n";
				$genCode .= "                 ]),\n";
				$genCode .= "            ],\n";
				
				// now "name" will be generated...
				$genCode .= "            '" . $column->name . $fieldFormat . "',\n";
			}
			// ...}
        
        if (++$count < 6) {
		
        	if ($setRelationInformation==1 || $useGenCode==1)
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
        ],
    ]); ?>

	<?= "<?php " ?>
	if (\vendor\meta_grid\helper\Utils::get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	<?php // echo "</div>\n"; ?>
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
