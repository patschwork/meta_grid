<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DataTransferProcess */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Data Transfer Process')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Transfer Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-transfer-process-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'data_transfer_typeList' => $data_transfer_typeList,		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $deleted_statusList,		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
						'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
	]) ?>

</div>