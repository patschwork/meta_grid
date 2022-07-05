<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\ObjectDependsOn */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Object Depends Ons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="object-depends-on-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-objectdependson')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-objectdependson')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'ref_fk_object_id_parent',
            'ref_fk_object_type_id_parent',
            'ref_fk_object_id_child',
            'ref_fk_object_type_id_child',
        ],
    ]) ?>

	
	
	
	
	
    	<?php
	
			
	
	    		
	?>  
    
    
</div>
