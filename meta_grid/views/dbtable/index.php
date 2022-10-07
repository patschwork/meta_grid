<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
</style>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use vendor\meta_grid\helper\RBACHelper;
use app\models\VDbTableSearchinterface;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DbTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Db Tables');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="db-table-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
// Das ist nicht der Yii2-Way, ... @ToDo
if (isset($_GET["searchShow"]))
{
	echo $this->render('_search', ['model' =>$searchModel]);
}
else
{
	echo "<a class='btn btn-default' href='index.php?r=".$_GET["r"]."&searchShow=1'>".Yii::t('app', 'Advanced Search')."</a></br></br>";
}
?>

    <p>
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtable')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Db Table'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
		<?php				$request = Yii::$app->request;
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
		?>	</p>

	<?php
	$session = Yii::$app->session;
	
	// Inform user about set perspective_filter
	if (array_key_exists("fk_object_type_id",  $searchModel->attributes) === true && (isset($searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === true))
	{
		$fk_object_type_id=$searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;
		if ($session->hasFlash('perspective_filter_for_' . $fk_object_type_id))
		{	
			echo yii\bootstrap4\Alert::widget([
					'options' => [
									'class' => 'alert-info',
					],
					'body' => $session->getFlash('perspective_filter_for_' . $fk_object_type_id),
			]);
		}		
	}
	
	if ($session->hasFlash('deleteError'))
	{	
		echo yii\bootstrap4\Alert::widget([
				'options' => [
					'class' => 'alert alert-danger alert-dismissable',
				],
				'body' => $session->getFlash('deleteError'),
		]);
	}

	\yii\helpers\Url::remember($url = '', $name = Yii::$app->controller->id."/INDEX");
	?>
	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-db-table', 'class' => 'table table-striped table-bordered'],
		'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
			'class' => 'yii\bootstrap4\LinkPager'
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
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            ,
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject->fkClient === NULL ? Yii::t('app', "Can't lookup the client name (for project {fk_project_id})", ['fk_project_id' => $model->fk_project_id]) : $model->fkProject->fkClient->name);
             		},
             		'filter' => Select2::widget([
             				'model' => $searchModel,
             				'attribute' => 'fk_client_id',
             				'data' => ArrayHelper::map(VDbTableSearchinterface::find()->select(['fk_client_id', 'client_name'])->distinct()->asArray()->all(), 'fk_client_id', 'client_name'),
             				'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_client_id'],
             				'pluginOptions' => [
             						'allowClear' => true
             				],
             		]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkProject->fkClient === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : ($model->fkProject=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkProject', 'this_id' => $model->fk_project_id]) : $model->fkProject->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_project_id',
            		'data' => ArrayHelper::map(VDbTableSearchinterface::find()->select(['fk_project_id', 'project_name'])->distinct()->asArray()->all(), 'fk_project_id', 'project_name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkProject === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            [
             'label' => Yii::t('app', 'Database'),
             'attribute' => 'databaseInfoFromLocation',
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'databaseInfoFromLocation',
            		'data' => ArrayHelper::map(app\models\VDbTableSearchinterface::find()->asArray()->all(), 'databaseInfoFromLocation', 'databaseInfoFromLocation'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_databaseInfoFromLocation'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
                 ]),
            ],
            'name:ntext',
            [
             'label' => Yii::t('app', 'Db Table Context'),
             'value' => function($model) {
             		return $model->fk_db_table_context_id == "" ? $model->fk_db_table_context_id : (isset($_GET["searchShow"]) ? $model->fkDbTableContext->name . ' [' . $model->fk_db_table_context_id . ']' : ($model->fkDbTableContext=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkDbTableContext', 'this_id' => $model->fk_db_table_context_id]) : $model->fkDbTableContext->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_db_table_context_id',
            		'data' => ArrayHelper::map(app\models\DbTableContext::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDbTableContext', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkDbTableContext === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            [
             'label' => Yii::t('app', 'Db Table Type'),
             'value' => function($model) {
             		return $model->fk_db_table_type_id == "" ? $model->fk_db_table_type_id : (isset($_GET["searchShow"]) ? $model->fkDbTableType->name . ' [' . $model->fk_db_table_type_id . ']' : ($model->fkDbTableType=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkDbTableType', 'this_id' => $model->fk_db_table_type_id]) : $model->fkDbTableType->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_db_table_type_id',
            		'data' => ArrayHelper::map(app\models\DbTableType::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDbTableType', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkDbTableType === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
        ],
    ]); ?>

	<?php 	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-db-table', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>
