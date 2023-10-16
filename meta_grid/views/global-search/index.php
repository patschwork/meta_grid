
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Project; 
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use app\models\Client; 
use app\models\VAllObjectsUnion;
use app\models\Objectcomment;
use app\models\ObjectType;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GlobalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Search');
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>

<div class="vall-objects-union-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
echo $this->render('_search', ['model' =>$searchModel]);
?>
	    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
		],
		'layout' => "{pager}\n{summary}{items}\n{pager}",
       	// 'rowOptions' => function ($model, $key, $index, $grid) {
       		// $controller = Yii::$app->controller->id;
       		// return [
       				// 'ondblclick' => 'location.href="'
       				// . Yii::$app->urlManager->createUrl([$controller . '/view','id'=>$key])
       				// . '"',
       		// ];
       	// },    
        'filterModel' => $searchModel,
        'columns' => [
            [
				// erzeugt einen Link zu dem Mappingobjekt
				// siehe auch: http://www.yiiframework.com/forum/index.php/topic/49595-how-to-change-buttons-in-actioncolumn/
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view}',
	            'buttons' => [
	            		'view' => function ($url, $model) {
	            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
									'target' => '_blank',
	            					'title' => Yii::t('app', "Open detail view of object"),
	            			]);
	            		}
	            ],
	            'urlCreator' => function ($action, $model, $key, $index) {
	            	if ($action === 'view') {
	            		$cntrl="";
	            		$link_to_objId=explode(";",$model->listkey)[0];
	            		$link_to_objTypeId=explode(";",$model->listkey)[1];
	            		
	            		$cntrl=str_replace("_","",$model->object_type_name);
	            		
	            		// Wenn es ein Kommentar ist, dann zu dem ObjectTyp des Kommentars linken.
	            		if ($link_to_objTypeId==12)
	            		{
	            			$comment_model  = Objectcomment::findOne($link_to_objId);
	            			
	            			$objIdFromComment = $comment_model->ref_fk_object_id;
	            			$objIdTypeFromComment = $comment_model->ref_fk_object_type_id;
	            			
	            			$object_type_model = ObjectType::findOne($objIdTypeFromComment);
	            			
	            			// Variablen f�r Link URL neu setzen
	            			$link_to_objId = $objIdFromComment;
	            			$cntrl=str_replace("_","",$object_type_model->name);
	            		}
	            		
	            		$url = "?r=$cntrl/view&id=".$link_to_objId; // your own url generation logic
	            		return $url;
	            	}
	            }
	             
            ],    
        	
        	['class' => 'yii\grid\SerialColumn'],
            'name:html',
			[
			    'label' => Yii::t('app', 'object_type_name'),
				'filter' => Select2::widget([
						'model' => $searchModel,
						'attribute' => 'object_type_name',
						'data' => ArrayHelper::map(VAllObjectsUnion::find()->asArray()->all(), 'object_type_name', 'object_type_name'),
						'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_object_type_name'],
						'pluginOptions' => [
								'allowClear' => true,
								'multiple' => true
							],
				 ]),
				 'value' => function($model) {
             		return $model->object_type_name;
             		},
			],
            // 'object_type_name:ntext',
            // 'listvalue_1',
            // 'listvalue_2',
            // 'listkey',
/*            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_client_id == "" ? $model->fk_client_id : (isset($_GET["searchShow"]) ? $model->fkClient->name . ' [' . $model->fk_client_id . ']' : $model->fkClient->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_client_id',
            		'data' => ArrayHelper::map(app\models\Client::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkClient'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],            
*/
            [
             'label' => Yii::t('app', 'Project'),
             'value' => function($model) {
             		// return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name);
             		return $model->fk_project_id;
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_project_id',
            		'data' => ArrayHelper::map(app\models\Project::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
        ],
    ]); ?>
	
</div>
