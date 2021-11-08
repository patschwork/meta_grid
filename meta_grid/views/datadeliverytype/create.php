<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DataDeliveryType */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Data Delivery Type')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Delivery Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-delivery-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>