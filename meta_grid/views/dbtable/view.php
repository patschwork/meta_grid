<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}


/* @var $this yii\web\View */
/* @var $model app\models\DbTable */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtable')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>
		
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-dbtable')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : "" ?>
		    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uuid:ntext',
            'fk_object_type_id',
            [
             'label' => Yii::t('app', 'Client'),
             'value' =>              		$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>              	$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name
            ],
            'name:ntext',
            'description:html',
            'location:ntext',
            [
             'label' => Yii::t('app', 'Db Table Context'),
             'value' =>              	$model->fk_db_table_context_id == "" ? $model->fk_db_table_context_id : $model->fkDbTableContext->name
            ],
            [
             'label' => Yii::t('app', 'Db Table Type'),
             'value' =>              	$model->fk_db_table_type_id == "" ? $model->fk_db_table_type_id : $model->fkDbTableType->name
            ],
        ],
    ]) ?>

	
	
	
	
	
    	<?php
	
			
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
					'active' => true,
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
					'active' => false,
					'options' => ['id' => 'tabFields']  // important for shortcut
				],
		],
		]);
		// Kommentierung pro Object ...}
	
	    		
	?>  
    
    
</div>
