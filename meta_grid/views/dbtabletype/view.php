<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}


/* @var $this yii\web\View */
/* @var $model app\models\DbTableType */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtabletype')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-dbtabletype')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'name:ntext',
            'description:html',
        ],
    ]) ?>

	
	
	
	
	
    	<?php
	
			
	
	    		
	?>  
    
    
</div>
