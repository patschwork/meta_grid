
<style>
* {
  margin: 0;
  padding: 0;
}

.loader {
  display: none;
  top: 50%;
  left: 50%;
  position: fixed;
  transform: translate(-50%, -50%);
}

.loading {
  border: 4px solid #ccc;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border-top-color: #1ecd97;
  border-left-color: #1ecd97;
  animation: spin 1s infinite ease-in;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>


<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Objectcomment;
use app\models\ObjectType;
use app\models\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\MapObject2ObjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = Yii::t('app', 'Map Object2 Objects');
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-object2-object-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <br>

    <?php 

		echo Html::a(Yii::t('app', 'Create new mapping'), ['mapper/createexternal', 'ref_fk_object_id' => $dataProvider->query->where["filter_ref_fk_object_id"], 'ref_fk_object_type_id' => $dataProvider->query->where["filter_ref_fk_object_type_id"]],['class' => 'btn btn-primary'], [
		'data' => [
		'method' => 'post',
		],
		])
		
	?>    
    
    <p>
        <!-- <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Map Object2 Object',
]), ['create'], ['class' => 'btn btn-success']) ?>   -->
    </p>

	<?php yii\widgets\Pjax::begin(['id' => 'mapping_grid_pjax']); ?>


	<?php
	// Yii::$app->cache->flush(); // resets the complete cache
	$filter_ref_fk_object_id = $dataProvider->query->where["filter_ref_fk_object_id"];
	$ref_fk_object_type_id = $dataProvider->query->where["filter_ref_fk_object_type_id"];
	$dependency = [
		'class' => 'yii\caching\DbDependency',
		'sql' => "SELECT MAX(log_datetime) FROM map_object_2_object_log WHERE 1=1 AND (ref_fk_object_type_id_1=$ref_fk_object_type_id OR ref_fk_object_type_id_2=$ref_fk_object_type_id) AND (ref_fk_object_id_1=$filter_ref_fk_object_id OR ref_fk_object_id_2=$filter_ref_fk_object_id)",
	];
	$variations = [Yii::$app->user->id, Yii::$app->language];
	$duration = \vendor\meta_grid\helper\Utils::get_app_config("cache_duration_mappings_list");
	$cacheid = "cache_".Yii::$app->controller->id."_".$filter_ref_fk_object_id;
	if ($this->beginCache($cacheid, ['dependency' => $dependency, 'variations' => $variations, 'duration' => $duration]))
	{
		echo  GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'rowOptions' => function ($model, $index, $widget, $grid){
				$bckColor="#FFF0B5";
				if ((strpos($model->name, 'MIP_T')) && ($model->connectiondirection === "->"))
				{
					return ['style'=>'background-color:'.$bckColor.';'];
				}
				else
				{
					return [];
				}				  
			},
			'columns' => [
				[
					// erzeugt einen Link zu dem Mappingobjekt
					// siehe auch: http://www.yiiframework.com/forum/index.php/topic/49595-how-to-change-buttons-in-actioncolumn/
					'class' => 'yii\grid\ActionColumn',
					'template' => '{view}',
					'buttons' => [
							'info' => function ($url, $model) {
								return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', $url, [
										'title' => Yii::t('app', 'Info'),
								]);
							},
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

					// 'connectiondirection',
				[
					'class' => 'yii\grid\DataColumn',
					'attribute' => 'connectiondirection',
					'format' => 'raw',				
					'label' => 'Direction',
					'value' => function ($model) {
							$retValue = '<span style="color: #204d74;" class="glyphicon glyphicon-arrow-left"></span>';
							if ($model->connectiondirection === "->")
							{
								$retValue = '<span style="color: #337ab7;" class="glyphicon glyphicon-arrow-right"></span>';
							}
							return "<div name='direction_arrow_" . $model->id . "'>" . $retValue . "</div>";
						}			
				],
				[
					'class' => 'yii\grid\DataColumn',
					'attribute' => 'name',
					'format' => 'html',				
					'label' => 'Content',
					'value' => function ($model) {

						$link_to_objId=explode(";",$model->listkey)[0];
						$link_to_objTypeId=explode(";",$model->listkey)[1];
						
						$cntrl=str_replace("_","",$model->object_type_name);

						$retValue = $model->name;

						// If it is an url, then show the corresponding link
						if ($link_to_objTypeId==24)
						{
							$url_model  = Url::findOne($link_to_objId);

							$urlToShow = $url_model->url;
							if (strlen($urlToShow)>50)  $urlToShow = substr($urlToShow,0,50) . "...";
							$urlToOpen = $url_model->url;
							
							$retValue = $model->name . " ". "<a href=\"$urlToOpen\">" . '<span class="glyphicon glyphicon-globe"></span>' . " $urlToShow</a>" . "";
						}

						return $retValue;
					}			
				],
				[
					'class' => 'yii\grid\DataColumn',
					'attribute' => 'object_type_name',
					'format' => 'text',
					'label' => 'Type',
				],
				[
					'class' => 'yii\grid\DataColumn',
					'attribute' => 'object_type_name',
					'format' => 'text',
					//'label' => 'Additional information',
					'header' => '<span class="glyphicon glyphicon-info-sign"></span>',
					'value' => function ($model) {
						
						$innerbracket = "";
						if (strpos($model->listvalue_2, '('))
						{
							if (strpos($model->listvalue_2, ')'))
							{
							
							$innerbracket_1 = explode("(",$model->listvalue_2)[1];
							$innerbracket_2 = explode(")", $innerbracket_1)[0];
							$innerbracket = $innerbracket_2;
							}
						}
						$retValue = $innerbracket;
						return $retValue;
					}	
				],     
				[
					'class' => 'yii\grid\ActionColumn',
					'template' => '{change_direction} {delete}',
					'buttons' => [
							'change_direction' => function ($url, $model) {
								return Html::button('<i class="glyphicon glyphicon-retweet"></i>',  
									['value' =>'Changing mapping direction','style' =>'background:none;border:none;','onclick'=>'callChangeDirection('.$model->id.')']);
							},
							'delete' => function ($url, $model) {
								return Html::button('<i class="glyphicon glyphicon-trash"></i>',  
									['value' =>'Changing mapping direction','style' =>'background:none;border:none;','onclick'=>'callRemoveMapping('.$model->id.')']);
							},
					'visibleButtons' => [
						'change_direction' => function ($model) {
							return \Yii::$app->user->can('create-mapper', ['post' => $model]);
						},
						'delete' => function ($model) {
							return \Yii::$app->user->can('delete-mapper', ['post' => $model]);
						},
					]
					],
				],   
			],
		]); 
	
		$this->endCache();
	}
	
	?>
<div class="loader">
  <div class="loading">
  </div>
</div>
<?php yii\widgets\Pjax::end(); ?>
<?php
					$urlAjaxCallChangeDirection = Yii::$app->getUrlManager()->createUrl('mapper/changedirectionajax');
					$urlAjaxCallRemoveMapping = Yii::$app->getUrlManager()->createUrl('mapper/deleteajax');
					$errorMsg = Yii::t('app', 'Error at changing direction call!');
	    			echo $this->registerJs(
						"
						function spinner() {
							document.getElementsByClassName(\"loader\")[0].style.display = \"block\";
						}

						function callChangeDirection(id) {
							spinner();
							$.ajax({
								type: 'POST',
								url: '$urlAjaxCallChangeDirection',
								data: {'id': id},
								success: function (data) {
									if (data > 0)
									{	
										$.pjax.reload({container: '#mapping_grid_pjax', async: false});
									}
									else
									{
										alert('$errorMsg' + \"\\n\" + 'Error code: ' + data);
										$.pjax.reload({container: '#mapping_grid_pjax', async: false});
									}
								},
								error: function (exception) {
									alert('$errorMsg: ' + exception);
									$.pjax.reload({container: '#mapping_grid_pjax', async: false});
								}
							})
							;
						}						
						
						function callRemoveMapping(id) {
							spinner();
							$.ajax({
								type: 'POST',
								url: '$urlAjaxCallRemoveMapping',
								data: {'id': id},
								success: function (data) {
									if (data > 0)
									{
										$.pjax.reload({container: '#mapping_grid_pjax', async: false});
									}
									else
									{
										alert('$errorMsg' + \"\\n\" + 'Error code: ' + data);
										$.pjax.reload({container: '#mapping_grid_pjax', async: false});
									}
								},
								error: function (exception) {
									alert('$errorMsg: ' + exception);
									$.pjax.reload({container: '#mapping_grid_pjax', async: false});
								}
							})
							;
						}
						",
						yii\web\View::POS_HEAD,
						null
				);
?>

</div>
