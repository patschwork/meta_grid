<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}

use dmstr\web\MermaidAsset;
MermaidAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\DataDeliveryObject */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Delivery Objects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-delivery-object-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-datadeliveryobject')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-datadeliveryobject')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            [
             'label' => Yii::t('app', 'Tool'),
             'value' =>              	$model->fk_tool_id == "" ? $model->fk_tool_id : $model->fkTool->tool_name
            ],
            [
             'label' => Yii::t('app', 'Data Delivery Type'),
             'value' =>              	$model->fk_data_delivery_type_id == "" ? $model->fk_data_delivery_type_id : $model->fkDataDeliveryType->name
            ],
            [
             'label' => Yii::t('app', 'Contact Group As Data Owner'),
             'value' =>              	$model->fk_contact_group_id_as_data_owner == "" ? $model->fk_contact_group_id_as_data_owner : $model->fkContactGroupIdAsDataOwner->name
            ],
            [
             'label' => Yii::t('app', 'Deleted Status'),
             'value' =>              	$model->fk_deleted_status_id == "" ? $model->fk_deleted_status_id : $model->fkDeletedStatus->name
            ],
            'source_definition:ntext',
            'source_comment:ntext',
            [
             'label' => Yii::t('app', 'Object Persistence Method'),
             'value' =>              	$model->fk_object_persistence_method_id == "" ? $model->fk_object_persistence_method_id : $model->fkObjectPersistenceMethod->name
            ],
            [
             'label' => Yii::t('app', 'Datamanagement Process'),
             'value' =>              	$model->fk_datamanagement_process_id == "" ? $model->fk_datamanagement_process_id : $model->fkDatamanagementProcess->name
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
						],
		]);
		// Kommentierung pro Object ...}
	
	    		
	?>  
    
    
</div>
