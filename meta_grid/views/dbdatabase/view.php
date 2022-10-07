<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\DbDatabase */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Databases'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="db-database-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbdatabase')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-dbdatabase')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            [
             'label' => Yii::t('app', 'Tool'),
             'value' =>         	    $model->fk_tool_id == "" ? $model->fk_tool_id : ($model->fkTool === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkTool', 'this_id' => $model->fk_tool_id]) : $model->fkTool->tool_name)
            ],
            [
             'label' => Yii::t('app', 'Bulkloader Execution Script'),
             'value' => '<button class="btn btn-default" type="button" id="btn_show_code" onclick="document.getElementById(\'bulkloaderExecutionString\').style.display=\'block\'; document.getElementById(\'btn_show_code\').style.display=\'none\';">' . Yii::t('app', 'Show') . '</button><div id="bulkloaderExecutionString" style="display: none;"><pre>' . $bulkloaderExecutionString . "</pre></div>",
             'format' => 'raw',
             'visible' => Yii::$app->user->identity->isAdmin || Yii::$app->User->can('show-bulkloader-template-in-dbdatabase')
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
