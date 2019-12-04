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

    <?= GridView::widget([
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

            	// 'connectiondirection',
			[
	            'class' => 'yii\grid\DataColumn',
	            'attribute' => 'connectiondirection',
	            'format' => 'html',				
	            'label' => 'Direction',
				'value' => function ($model) {
						$retValue = '<span style="color: #204d74;" class="glyphicon glyphicon-arrow-left"></span>';
						if ($model->connectiondirection === "->")
						{
							$retValue = '<span style="color: #337ab7;" class="glyphicon glyphicon-arrow-right"></span>';
						}
						return $retValue;
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
           ],
    ]); ?>

</div>
