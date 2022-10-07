<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Map Object2object')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Map Object2objects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-object2-object-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'mapping_qualifierList' => $mapping_qualifierList,		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
						'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
	]) ?>

</div>