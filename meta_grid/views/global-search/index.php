
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

$dependency = new yii\caching\DbDependency();
$dependency->sql='SELECT max(log_datetime) FROM "v_LastChangesLog_List"';
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
            // 'name:html',
// 					$value = $model->object_type_name."<br>".$model->detail_1_content;


			[
				'label' => Yii::t('app', 'name'),
				'value' => function($model) {
					$value = $model->name;
					if ($model->fk_deleted_status_id !== NULL)
					{
						if ($model->fk_deleted_status_id>0)
						{
							$value = '<del>'.'<span style="color:red;">'.$model->name.'</span>'.'</del>';
							// .' '.'<sub>'.$model->deleted_status_name.'</sub>';
						}
					}
					// $value = $model->name."<br>"."<small>".$model->detail_1_content."</small>";
					if ($model->detail_1_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_1_name.": "."</b>".$model->detail_1_content."</small>";
					}
					if ($model->detail_2_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_2_name.": "."</b>".$model->detail_2_content."</small>";
					}
					if ($model->detail_3_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_3_name.": "."</b>".$model->detail_3_content."</small>";
					}
					if ($model->detail_4_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_4_name.": "."</b>".$model->detail_4_content."</small>";
					}
					if ($model->detail_5_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_5_name.": "."</b>".$model->detail_5_content."</small>";
					}

					return $value;
					},
				'format' => 'html',
                'contentOptions' => function($model) {
                    // needs to be closure because of title
					if (($model->description !== NULL) && ($model->description !== '(Added via Bulk Import)'))
					{
                    return [
                        'class' => 'cell-with-tooltip',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top', // top, bottom, left, right
                        'data-container' => 'body', // to prevent breaking table on hover
                        // 'title' => strip_tags($data->description),
                        'title' => "<b><i>".Yii::t('app', 'Description')."</i></b>:<br>".$model->description, // use html
					];
					} else return [];
                }
			],



			[
			    'label' => Yii::t('app', 'object_type_name'),
				'filter' => Select2::widget([
						'model' => $searchModel,
						'attribute' => 'object_type_name',
						'data' => ArrayHelper::map(VAllObjectsUnion::find()->select(['object_type_name'])->distinct()->cache(NULL, $dependency)->asArray()->all(), 'object_type_name', 'object_type_name'),
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
						return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name . " (" . $model->fkProject->fkClient->name .")";
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
<?php 
$js = <<<SCRIPT
/* To initialize BS3 tooltips set this below */
$(function () { 
   $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
        html:true
    });
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs ( $js );
?>
