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
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ImportStageDbTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Import Stage Db Tables');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<h2>The import stage is a <span style="color: red">beta</span> feature!</h2>

<?php
       	$Utils = new \vendor\meta_grid\helper\Utils();
		$beta_Features_enabled = $Utils->get_app_config("enable_beta_features");

        if ($beta_Features_enabled !== 1)
        {
            echo yii\bootstrap4\Alert::widget([
                'options' => [
                        'class' => 'alert-warning',
                ],
                'body' => Yii::t('app','You are trying to open a beta feature. Beta features are currently not accessable. Please ask your administrator to enable them in the config.'),
            ]);
            return;
        }
?>

<div class="import-stage-db-table-index">

    <h1><?= Html::encode($this->title) ?></h1>
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
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-importstagedbtable')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Import Stage Db Table'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
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

	Url::remember();
	?>

		<?=Html::beginForm(['processselected'],'post');?>
		<?=Html::dropDownList('action','',['0'=>'Import','1'=>'Delete from list','2'=>'Import all','3'=>'Delete all'],['class'=>'dropdown',])?>
		<?=Html::submitButton(Yii::t('app','Process'), ['class' => 'btn btn-primary']);?>

	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-import-stage-db-table', 'class' => 'table table-striped table-bordered'],
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
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            ,
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            ],
			[
				'class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => function($model) {
					  return ['value' => $model->id];
				  },
			],        	
        	['class' => 'yii\grid\SerialColumn'],

            'id',
            'client_name:ntext',
            'project_name:ntext',
            'db_table_name:ntext',
            'db_table_description:ntext',
            'db_table_field_name:ntext',
            'db_table_field_datatype:ntext',
            'db_table_field_description:ntext',
            'db_table_type_name:ntext',
            'db_table_context_name:ntext',
            'db_table_context_prefix:ntext',
            'isPrimaryKeyField:boolean',
            'isForeignKeyField:boolean',
            'foreignKey_table_name:ntext',
            'foreignKey_table_field_name:ntext',
            '_import_state',
            // '_import_date:ntext',
            'is_BusinessKey:boolean',
            'is_GDPR_relevant:boolean',
            'database_or_catalog',
            'schema',
/*            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name;
             		},
             		'filter' => Select2::widget([
             				'model' => $searchModel,
             				'attribute' => 'fk_client_id',
             				'data' => ArrayHelper::map(VImportStageDbTableSearchinterface::find()->select(['fk_client_id', 'client_name'])->distinct()->asArray()->all(), 'fk_client_id', 'client_name'),
             				'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_client_id'],
             				'pluginOptions' => [
             						'allowClear' => true
             				],
             		]),
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_project_id',
            		'data' => ArrayHelper::map(VImportStageDbTableSearchinterface::find()->select(['fk_project_id', 'project_name'])->distinct()->asArray()->all(), 'fk_project_id', 'project_name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],

/*            [
             'label' => Yii::t('app', 'Db Database'),
             'value' => function($model) {
             		return $model->fk_db_database_id == "" ? $model->fk_db_database_id : (isset($_GET["searchShow"]) ? $model->fkDbDatabase->name . ' [' . $model->fk_db_database_id . ']' : $model->fkDbDatabase->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_db_database_id',
            		'data' => ArrayHelper::map(app\models\DbDatabase::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDbDatabase', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
*/            // 'column_default_value',
            'column_cant_be_null:boolean',
            // 'additional_field_1',
            // 'additional_field_2',
            // 'additional_field_3',
            // 'additional_field_4',
            // 'additional_field_5',
            // 'additional_field_6',
            // 'additional_field_7',
            // 'additional_field_8',
            // 'additional_field_9',
        ],
    ]); ?>

	<?= Html::endForm();?> 

	<?php 	
   	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-import-stage-db-table', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>