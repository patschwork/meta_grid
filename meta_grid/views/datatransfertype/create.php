<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DataTransferType */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Data Transfer Type')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Transfer Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-transfer-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
						'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
	]) ?>

</div>