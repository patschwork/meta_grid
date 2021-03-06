<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Map Object2 Object')]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Map Object2 Objects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="map-object2-object-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'mapping_qualifierList' => $mapping_qualifierList,		// autogeneriert ueber gii/CRUD
        
				    ]) ?>

</div>