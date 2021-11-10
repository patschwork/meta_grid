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

<?php if ($generator->modelClass === "app\models\Bracket"): ?>
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
<?php endif; ?>
use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

<?php if ($generator->modelClass === "app\models\Bracket"): ?>
$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {

    jQuery(".dynamicform_wrapper .panel-title-bracketsearchpattern").each(function(index) {

        jQuery(this).html("BracketSearchPattern: " + (index + 1))

    });

});


jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {

    jQuery(".dynamicform_wrapper .panel-title-bracketsearchpattern").each(function(index) {

        jQuery(this).html("BracketSearchPattern: " + (index + 1))

    });

});

';

$this->registerJs($js);
<?php endif; ?>

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
<?php
$is_DbTable_Or_DbTableField = false;
if ($generator->modelClass === 'app\models\DbTable' || $generator->modelClass === 'app\models\DbTableField') $is_DbTable_Or_DbTableField = true;
?>
<?php if (! $is_DbTable_Or_DbTableField): ?>
        <?= "<?= Yii::\$app->user->identity->isAdmin || Yii::\$app->User->can('create-" . str_replace("controller", "", strtolower ( StringHelper::basename($generator->controllerClass) ) ) . "')  ? " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) : "" ?>
<?php endif; ?>
<?php if ($is_DbTable_Or_DbTableField): ?>
<?php $dbtableadditioncode1 = "";
$dbtableadditioncode2 = "";
$dbtablefieldadditioncode1 = "id";
$dbtablefieldadditioncode2 = "";
if ($generator->modelClass === 'app\models\DbTable')
{
	$dbtableadditioncode1 = "&& Yii::\$app->User->can('create-dbtable')";
	$dbtableadditioncode2 = " table";
	echo "\t<?= Yii::\$app->user->identity->isAdmin || (Yii::\$app->User->can('create-dbtablefield')) $dbtableadditioncode1 ? Html::a(Yii::t('app', 'Update table and fields'), ['dbtablefieldmultipleedit/update', 'id' => \$model->$dbtablefieldadditioncode1], ['class' => 'btn btn-primary']) : \"\" ?>";
}
if ($generator->modelClass === 'app\models\DbTableField')
{
	$dbtablefieldadditioncode1 = "fk_db_table_" . $dbtablefieldadditioncode1;
	$dbtablefieldadditioncode2 = "field";
	echo "\t<?= Yii::\$app->user->identity->isAdmin || (Yii::\$app->User->can('create-dbtablefield')) $dbtableadditioncode1 ? Html::a(Yii::t('app', 'Update table and fields'), ['dbtablefieldmultipleedit/update', 'id' => \$model->$dbtablefieldadditioncode1, '#' => \$model->id], ['class' => 'btn btn-primary']) : \"\" ?>";
}

?>
	<?= "

		<?php
			\$db_table_show_buttons_for_different_object_type_updates = \\vendor\\meta_grid\\helper\\Utils::get_app_config(\"db_table_show_buttons_for_different_object_type_updates\");
			if (\$db_table_show_buttons_for_different_object_type_updates == 1) 
			{
				echo Yii::\$app->user->identity->isAdmin || Yii::\$app->User->can('create-dbtable$dbtablefieldadditioncode2')  ? Html::a(Yii::t('app', 'Update$dbtableadditioncode2'), ['update', 'id' => \$model->id], ['class' => 'btn btn-primary']) : \"\";
			}
		?>" ?>
<?php endif; ?>

<?php if ($generator->modelClass !== "app\models\Bracket"): ?>
        <?= "<?= Yii::\$app->user->identity->isAdmin || Yii::\$app->User->can('delete-" . str_replace("controller", "", strtolower ( StringHelper::basename($generator->controllerClass) ) ) . "')  ? " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                'method' => 'post',
            ],
        ]) : "" ?>
<?php endif; ?>	
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
		$useGenCode = 0;
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
    			$genCode .= "             'label' => Yii::t('app', 'Client'),\n";
    			$genCode .= "             'value' => ";
    			$genCode .= "             		\$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->fkClient->name\n";
    			$genCode .= "            ],\n";
    		
    		}
    		
    		$nameFieldProperty = "name";
    		// Ausnahmen definieren
    		if ($column->name=="fk_tool_id") $nameFieldProperty = "tool_name";
    		if ($column->name=="fk_contact_group_id_as_data_owner") $relFieldname = "fkContactGroupIdAsDataOwner";
    		if ($column->name=="fk_contact_group_id_as_supporter") $relFieldname = "fkContactGroupIdAsSupporter";
			if ($column->name=="fk_object_type_id_as_searchFilter") $relFieldname = "fkObjectTypeIdAsSearchFilter";
    		
    		$genCode .= "            [\n";
    		$genCode .= "             'label' => Yii::t('app', '$relFieldnameLabel'),\n";
    		$genCode .= "             'value' => ";
    		$genCode .= "             	\$model->".$column->name." == \"\" ? \$model->".$column->name." : \$model->".$relFieldname."->$nameFieldProperty\n";
    		$genCode .= "            ],\n";

    	}
    	
		if ($column->name=="formula" && $generator->modelClass=="app\models\Attribute")
		{
			$useGenCode = 1;
			$genCode  = "";
    		$genCode .= "            [\n";
    		$genCode .= "             'label' => Yii::t('app', 'Formula'),\n";
    		$genCode .= "             'value' => ";
    		$genCode .= "             	\$model->".$column->name." == \"\" ? NULL : \"<pre>\" . \$model->formulaWithLinks . \"</pre>\",\n";
    		$genCode .= "             'format' => 'html'\n";
    		$genCode .= "            ],\n";
			
		}

		if ($column->name=="fk_db_table_id")
		{
			$useGenCode = 1;
    		$genCode  = "";
    		$genCode .= "            [\n";
    		$genCode .= "             'label' => Yii::t('app', '$relFieldnameLabel'),\n";
           	$genCode .= "             'value' => Html::a(\$model->" . $column->name . " == \"\" ? \$model->" . $column->name . " : \$model->" . $relFieldname . "->$nameFieldProperty" . ", ['dbtable/view', 'id' => \$model->" . $column->name . "], ['class' => 'btn btn-default']),\n";
    		$genCode .= "             'format' => 'raw'\n";
    		$genCode .= "            ],\n";
		}



            // [
	            // 'attribute'=>'Db Table',
	            // 'format'=>'raw',
            	// 'value' => Html::a(Yii::t('app', $model->fk_db_table_id == "" ? $model->fk_db_table_id : $model->fkDbTable->name), ['dbtable/view', 'id' => $model->fk_db_table_id], ['class' => 'btn btn-default'])
            // ],

		
        $format = $generator->generateColumnFormat($column);

        // Formate für Splaten anpassen. z.B. gibt eine email Spalte
        $fieldFormat = "";
        if ($format != 'text')
        {
        	$fieldFormat = ":" . $format;
        	if ($column->name=="email") $fieldFormat = ":email";
        	if ($column->name=="description") $fieldFormat = ":html";
        	if ($column->name=="comment") $fieldFormat = ":html";
        }
        
        if ($setRelationInformation==1 || $useGenCode==1)
        {
        	echo $genCode;
        }
        else
        {
        	echo "            '" . $column->name . $fieldFormat . "',\n";
        }
	}
}
if ($generator->modelClass=="app\models\DbDatabase")
{
	$genCode  = "            [\n";
	$genCode .= "             'label' => Yii::t('app', 'Bulkloader Execution Script'),\n";
	$genCode .= "             'value' => ";
	$genCode .= "'<button class=\"btn btn-default\" type=\"button\" id=\"btn_show_code\" onclick=\"document.getElementById(\'bulkloaderExecutionString\').style.display=\'block\'; document.getElementById(\'btn_show_code\').style.display=\'none\';\">' . Yii::t('app', 'Show') . '</button><div id=\"bulkloaderExecutionString\" style=\"display: none;\"><pre>' . \$bulkloaderExecutionString . \"</pre></div>\",\n";
	$genCode .= "             'format' => 'raw',\n";
	$genCode .= "             'visible' => Yii::\$app->user->identity->isAdmin || Yii::\$app->User->can('show-bulkloader-template-in-dbdatabase')\n";
	$genCode .= "            ],\n";
	echo $genCode;
}
?>
        ],
    ]) ?>

	
<?php 
	if ($generator->modelClass === "app\models\Bracket")
	{
echo '    ' . "\n";
echo '    <div class="customer-form">' . "\n";
echo '    <?php ' . "\n";
echo '    	$form = ActiveForm::begin([\'id\' => \'dynamic-form\']); ' . "\n";
echo '    ?>' . "\n";
echo '    <div class="row">' . "\n";
echo '        <div class="col-sm-6">' . "\n";
echo '            <?php' . "\n";
echo '//             	 echo $form->field($modelBracket, \'name\')->textInput([\'maxlength\' => true]) ' . "\n";
echo '           	?>' . "\n";
echo '        </div>' . "\n";
echo '        <div class="col-sm-6">' . "\n";
echo '            <?php' . "\n";
echo '//             	 $form->field($modelBracket, \'description\')->textInput([\'maxlength\' => true]) ' . "\n";
echo '            ?>' . "\n";
echo '        </div>' . "\n";
echo '    </div>' . "\n";
echo '' . "\n";
echo '' . "\n";
echo '    <div class="padding-v-md">' . "\n";
echo '        <div class="line line-dashed"></div>' . "\n";
echo '    </div>' . "\n";
echo '' . "\n";
echo '    <?php DynamicFormWidget::begin([' . "\n";
echo '        \'widgetContainer\' => \'dynamicform_wrapper\', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]' . "\n";
echo '        \'widgetBody\' => \'.container-items\', // required: css class selector' . "\n";
echo '        \'widgetItem\' => \'.item\', // required: css class' . "\n";
echo '        \'limit\' => 4, // the maximum times, an element can be cloned (default 999)' . "\n";
echo '        \'min\' => 0, // 0 or 1 (default 1)' . "\n";
echo '        \'insertButton\' => \'.add-item\', // css class' . "\n";
echo '        \'deleteButton\' => \'.remove-item\', // css class' . "\n";
echo '        \'model\' => $modelsBracketSearchPattern[0],' . "\n";
echo '        \'formId\' => \'dynamic-form\',' . "\n";
echo '        \'formFields\' => [' . "\n";
echo '            \'searchPattern\',' . "\n";
echo '        ],' . "\n";
echo '' . "\n";
echo '    ]); ?>' . "\n";
echo '' . "\n";
echo '    <div class="panel panel-default">' . "\n";
echo '        <div class="panel-heading">' . "\n";
echo '            <i class="fa fa-envelope"></i> Bracket Search Pattern Definitions' . "\n";
echo '<!--             <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add Definition</button> -->' . "\n";
echo '            <div class="clearfix"></div>' . "\n";
echo '        </div>' . "\n";
echo '' . "\n";
echo '        <div class="panel-body container-items"><!-- widgetContainer -->' . "\n";
echo '            <?php foreach ($modelsBracketSearchPattern as $index => $modelBracketSearchPattern): ?>' . "\n";
echo '                <div class="item panel panel-default"><!-- widgetBody -->' . "\n";
/*
	echo '                    <div class="panel-heading">' . "\n";
	echo '                        <span class="panel-title-bracketsearchpattern">bracketsearchpattern_: <?= ($index + 1) ?></span>' . "\n";
	echo '<!--                         <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button> -->' . "\n";
	echo '                        <div class="clearfix"></div>' . "\n";
	echo '                    </div>' . "\n";
	echo '' . "\n";
*/
echo '                    <div class="panel-body">' . "\n";
echo '                        <?php' . "\n";
echo '                            // necessary for update action.' . "\n";
echo '                            if (!$modelBracketSearchPattern->isNewRecord) {' . "\n";
echo '                                echo Html::activeHiddenInput($modelBracketSearchPattern, "[{$index}]id");' . "\n";
echo '                            }' . "\n";
echo '                        ?>' . "\n";
echo '' . "\n";
echo '                        <?= $form->field($modelBracketSearchPattern, "[{$index}]searchPattern")->textInput([\'maxlength\' => true, \'readonly\' => true]) ?>' . "\n";
echo '' . "\n";
echo '                    </div>' . "\n";
echo '                </div>' . "\n";
echo '            <?php endforeach; ?>' . "\n";
echo '        </div>' . "\n";
echo '    </div>' . "\n";
echo '    <?php DynamicFormWidget::end(); ?>' . "\n";
echo '    <div class="form-group">' . "\n";
echo '        <?php' . "\n";
echo '//          Html::submitButton($modelBracketSearchPattern->isNewRecord ? \'Create\' : \'Update\', [\'class\' => \'btn btn-primary\']) ' . "\n";
echo '         ?>' . "\n";
echo '    </div>' . "\n";
echo '' . "\n";
echo '    <?php ActiveForm::end(); ?>' . "\n";
echo '' . "\n";
echo '' . "\n";
echo '</div>' . "\n";

	}
?>	
	
	
	
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
	<?php if ($generator->modelClass === "app\models\DbTable"): ?>
		 $provider = new yii\data\ArrayDataProvider([
			'allModels' => $sameTableList,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['id'],
			],
		]);

		echo yii\grid\GridView::widget([
			'dataProvider' => $provider,
			'columns' => [
				'id',
				'project_name',
				[
					'label' => 'db_table_location_normalized',
					'value' => function ($model) {
						return Html::a($model->location, ['dbtable/view', 'id' => $model->id], ['class' => 'btn btn-default']);
					},
					'format' => 'raw'
				],
			],
		]);
	<?php endif; ?>

	<?php if ($generator->modelClass === "app\models\DbTableField"): ?>
		$bracket_widget = \vendor\meta_grid\bracket_list\BracketListWidget::widget(
					    	[
				  				'object_type' => 'db_table_field',
					    		'fk_object_type_id' => $model->fk_object_type_id,
					    		'fk_object_id' => $model->id,
					    		'show_Matches' => true  
					    	]);
        
		// Wenn keine Bracket Elemente gefunden werden, liefert das Widget nichts zurück.
		// In diesem Fall soll der Tab deaktivert werden, um kenntlich zu machen, das keine Einträge existieren
        $bracket_tab_disabled = "";
        if ($bracket_widget=="") $bracket_tab_disabled = 'disabled';
	<?php endif; ?>
		
	<?php if (($generator->modelClass!=="app\models\DbTableType") && ($generator->modelClass!=="app\models\MapObject2Object") && ($generator->modelClass!=="app\models\ObjectDependsOn")): ?>
		// {... Kommentierung pro Object
		// 		autogeneriert ueber gii/CRUD
		$objectcommentSearchModel = new app\models\ObjectcommentSearch();
        
        $query = app\models\Objectcomment::find();
        $objectcommentDataProvider = new ActiveDataProvider([
        		'query' => $query,
				'pagination' => false,
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $model->id,
        		'ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
				'pagination' => false,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $model->id,
        		'filter_ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);

		<?php 
		if ($generator->modelClass=="app\models\DbTable")
		{
			echo "" . '$dbTableFieldSearchModel = new app\models\DbTableFieldSearch();' . "\n";
			echo "\t\t" . '$querydbTableField = app\models\DbTableFieldSearch::find();' . "\n";
			echo "\t\t" . '$dbTableFieldDataProvider = new ActiveDataProvider([' . "\n";
			echo "\t\t\t\t" . '\'query\' => $querydbTableField,' . "\n";
			echo "\t\t\t\t" . '\'pagination\' => false,' . "\n";
			echo "\t\t]);";
			echo "\t\t" . '$querydbTableField->andFilterWhere([' . "\n";
			echo "\t\t\t\t" . '\'fk_db_table_id\' => $model->id,' . "\n";
			echo "\t\t]);";				
		}
		?>     
        
		echo Tabs::widget([
			'items' => 
			[
				[
					'label' => Yii::t('app', 'Comments'),
					'content' => $this->render('../objectcomment/_index_external', [
								    'searchModel' => $objectcommentSearchModel,
            						'dataProvider' => $objectcommentDataProvider,
								    ]),
					'active' => <?= $generator->modelClass==="app\models\DbTable" ? "false" : "true" ?>,
					'options' => ['id' => 'tabComments']  // important for shortcut
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapper/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false,
					'options' => ['id' => 'tabMapping']  // important for shortcut
				],
		<?php 
		if ($generator->modelClass=="app\models\DbTableField")
		{
			echo "\t\t\t[" . "\n";
			echo "\t\t\t	'headerOptions' => [" . "\n";
			echo "\t\t\t		'class'=> \$bracket_tab_disabled" . "\n";
			echo "\t\t\t	]," . "\n";
			echo "\t\t\t	" . "\n";
			echo "\t\t\t	'label' => Yii::t('app', 'Brackets')," . "\n";
			echo "\t\t\t	'content' => \$bracket_widget," . "\n";
			echo "\t\t\t	'options' => ['id' => 'tabBracket']  // important for shortcut" . "\n";
			echo "\t\t\t]," . "\n";
		}
		?>
		<?php 
		if ($generator->modelClass=="app\models\DbTable")
		{
			echo "[" . "\n";
			echo "\t\t\t\t	'label' => Yii::t('app', 'Fields')," . "\n";
			echo "\t\t\t\t\t" . '\'content\' => $this->render(\'../dbtablefield/_index_external\', [' . "\n";
			echo "\t\t\t\t\t\t" . '\'searchModel\' => $dbTableFieldSearchModel,' . "\n";
			echo "\t\t\t\t\t\t" . '\'dataProvider\' => $dbTableFieldDataProvider,' . "\n";
			echo "\t\t\t\t\t\t" . '\'fk_db_table_id\' => $model->id,' . "\n";
			echo "\t\t\t\t" . "\n";
			echo "\t\t\t\t	])," . "\n";
			echo "\t\t\t\t	'active' => true," . "\n";
			echo "\t\t\t\t	'options' => ['id' => 'tabFields']  // important for shortcut" . "\n";
			echo "\t\t\t\t]," . "\n";
			echo "\t\t\t\t[" . "\n";
			echo "\t\t\t\t	'label' => Yii::t('app', 'SQL'),\n";
			echo "\t\t\t\t	'content' => \"<br><button class=\\\"btn btn-default\\\" id=\\\"btn_copy_SQLSelectStatement\\\">\" . Yii::t('app', 'Copy to clipboard') .  \"</button><br><br><pre id='SQLSelectStatement'>\$SQLSelectStatement</pre>\",\n";
			echo "\t\t\t\t	'active' => false,\n";
			echo "\t\t\t\t	'options' => ['id' => 'tabSQLExmaple'],  // important for shortcut\n";
			echo "\t\t\t\t	'headerOptions' => [\n";
			echo "\t\t\t\t		'class'=> \$SQLSelectStatement === \"\" ? 'disabled' : \"\"\n";
			echo "\t\t\t\t		]\n";
			echo "\t\t\t\t],\n";
		}
		?>
		],
		]);
		// Kommentierung pro Object ...}
	<?php endif; ?>

	    <?php 
		// bei Objekttyp "client" keine Kommentierung oder Mapping...
		if ($commentTabGeneration) echo "*/\n";
		?>
		<?php if ($generator->modelClass==="app\models\DbTable"): ?>
	echo $this->registerJs(
				"
				function copyFunction() {
					const copyText = document.getElementById(\"SQLSelectStatement\").textContent;
					const textArea = document.createElement('textarea');
					textArea.style.position = 'absolute';
					textArea.style.left = '-100%';
					textArea.textContent = copyText;
					document.body.append(textArea);
					textArea.select();
					document.execCommand(\"copy\");
					textArea.remove();
				}
				
				document.getElementById('btn_copy_SQLSelectStatement').addEventListener('click', copyFunction);
				",
				yii\web\View::POS_READY,
				null
		);
		<?php endif; ?>

	?>  
    
    
</div>
