<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Map Object2objects'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="map-object2-object-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-mapper')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-mapper')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'ref_fk_object_id_1',
            'ref_fk_object_type_id_1',
            'ref_fk_object_id_2',
            'ref_fk_object_type_id_2',
            [
             'label' => Yii::t('app', 'Mapping Qualifier'),
             'value' =>         	    $model->fk_mapping_qualifier_id == "" ? $model->fk_mapping_qualifier_id : ($model->fkMappingQualifier === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkMappingQualifier', 'this_id' => $model->fk_mapping_qualifier_id]) : $model->fkMappingQualifier->name)
            ],
        ],
    ]) ?>

	
	
	
	
	
    	<?php
	
			
	
	    		
	?>  
    
    
</div>
