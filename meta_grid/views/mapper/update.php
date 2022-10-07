<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Map Object2object')]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Map Object2objects'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="map-object2-object-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 
       'mapping_qualifierList' => $mapping_qualifierList,		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
        
						'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
    ]) ?>

</div>