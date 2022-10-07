<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\DbTable */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Tables'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="db-table-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
	<?= Yii::$app->user->identity->isAdmin || (Yii::$app->User->can('create-dbtablefield')) && Yii::$app->User->can('create-dbtable') ? Html::a(Yii::t('app', 'Update table and fields'), ['dbtablefieldmultipleedit/update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>	

		<?php
		    $Utils = new \vendor\meta_grid\helper\Utils();
			$db_table_show_buttons_for_different_object_type_updates = $Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");
			if ($db_table_show_buttons_for_different_object_type_updates == 1) 
			{
				echo Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtable')  ? Html::a(Yii::t('app', 'Update table'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "";
			}
		?>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-dbtable')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : "" ?>
	
    </p>

<?php
$TagsWidget = \vendor\meta_grid\tag_select\TagSelectWidget::widget(
	[
		'object_id' => $model->id,
		'object_type_id' => $model->fk_object_type_id,
		'user_id' => \Yii::$app->getUser()->id,
		'project_id' => $model->fkProject->id
	]);
?>	

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
             'label' => Yii::t('app', 'Tags'),
             'value' => $TagsWidget,
             'format' => 'raw',
            ],
            'id',
            'uuid:ntext',
            'fk_object_type_id',
            [
             'label' => Yii::t('app', 'Client'),
             'value' =>              		    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject->fkClient === NULL ? Yii::t('app', "Can't lookup the client name (for project {fk_project_id})", ['fk_project_id' => $model->fk_project_id]) : $model->fkProject->fkClient->name)
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>         	    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkProject', 'this_id' => $model->fk_project_id]) : $model->fkProject->name)
            ],
            'name:ntext',
            'description:html',
            'location:ntext',
            [
             'label' => Yii::t('app', 'Db Table Context'),
             'value' =>         	    $model->fk_db_table_context_id == "" ? $model->fk_db_table_context_id : ($model->fkDbTableContext === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkDbTableContext', 'this_id' => $model->fk_db_table_context_id]) : $model->fkDbTableContext->name)
            ],
            [
             'label' => Yii::t('app', 'Db Table Type'),
             'value' =>         	    $model->fk_db_table_type_id == "" ? $model->fk_db_table_type_id : ($model->fkDbTableType === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkDbTableType', 'this_id' => $model->fk_db_table_type_id]) : $model->fkDbTableType->name)
            ],
        ],
    ]) ?>

	
	
	
	
	
    	<?php
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

		$dbTableFieldSearchModel = new app\models\DbTableFieldSearch();
		$querydbTableField = app\models\DbTableFieldSearch::find();
		$dbTableFieldDataProvider = new ActiveDataProvider([
				'query' => $querydbTableField,
				'pagination' => false,
		]);		$querydbTableField->andFilterWhere([
				'fk_db_table_id' => $model->id,
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
					'active' => false,
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
				[
					'label' => Yii::t('app', 'Fields'),
					'content' => $this->render('../dbtablefield/_index_external', [
						'searchModel' => $dbTableFieldSearchModel,
						'dataProvider' => $dbTableFieldDataProvider,
						'fk_db_table_id' => $model->id,
				
					]),
					'active' => true,
					'options' => ['id' => 'tabFields']  // important for shortcut
				],
				[
					'label' => Yii::t('app', 'SQL'),
					'content' => "<br><button class=\"btn btn-default\" id=\"btn_copy_SQLSelectStatement\">" . Yii::t('app', 'Copy to clipboard') .  "</button><br><br><pre id='SQLSelectStatement'>$SQLSelectStatement</pre>",
					'active' => false,
					'options' => ['id' => 'tabSQLExmaple'],  // important for shortcut
					'headerOptions' => [
						'class'=> $SQLSelectStatement === "" ? 'disabled' : ""
						]
				],
		],
		]);
		// Kommentierung pro Object ...}
	
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
		
	?>  
    
    
</div>
