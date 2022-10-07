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
use app\models\VBracketSearchinterface;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BracketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Brackets');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="bracket-index">

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
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-bracket')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Bracket'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
					</p>

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
		'tableOptions' => ['id' => 'grid-view-bracket', 'class' => 'table table-striped table-bordered'],
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
             				'data' => ArrayHelper::map(VBracketSearchinterface::find()->select(['fk_client_id', 'client_name'])->distinct()->asArray()->all(), 'fk_client_id', 'client_name'),
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
            		'data' => ArrayHelper::map(VBracketSearchinterface::find()->select(['fk_project_id', 'project_name'])->distinct()->asArray()->all(), 'fk_project_id', 'project_name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkProject === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            'name:ntext',
            'description:html',
            [
             'label' => Yii::t('app', 'Attribute'),
             'value' => function($model) {
             		return $model->fk_attribute_id == "" ? $model->fk_attribute_id : (isset($_GET["searchShow"]) ? $model->fkAttribute->name . ' [' . $model->fk_attribute_id . ']' : ($model->fkAttribute=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkAttribute', 'this_id' => $model->fk_attribute_id]) : $model->fkAttribute->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_attribute_id',
            		'data' => ArrayHelper::map(app\models\Attribute::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkAttribute', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkAttribute === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            [
             'label' => Yii::t('app', 'Object Type As Search Filter'),
             'value' => function($model) {
             		return $model->fk_object_type_id_as_searchFilter == "" ? $model->fk_object_type_id_as_searchFilter : (isset($_GET["searchShow"]) ? $model->fkObjectTypeIdAsSearchFilter->name . ' [' . $model->fk_object_type_id_as_searchFilter . ']' : ($model->fkObjectTypeIdAsSearchFilter=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkObjectTypeIdAsSearchFilter', 'this_id' => $model->fk_object_type_id_as_searchFilter]) : $model->fkObjectTypeIdAsSearchFilter->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_object_type_id_as_searchFilter',
            		'data' => ArrayHelper::map(app\models\ObjectType::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkObjectTypeIdAsSearchFilter', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkObjectTypeIdAsSearchFilter === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
        ],
    ]); ?>

	<?php 	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-bracket', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>
