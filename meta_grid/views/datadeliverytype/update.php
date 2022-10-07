<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DataDeliveryType */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Data Delivery Type')]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Delivery Types'), 'url' => ['index']];
$bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="data-delivery-type-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
        
						'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
    ]) ?>

</div>