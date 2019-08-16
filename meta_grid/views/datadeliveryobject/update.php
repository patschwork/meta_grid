<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DataDeliveryObject */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Data Delivery Object')]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Delivery Objects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="data-delivery-object-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'toolList' => $toolList,		// autogeneriert ueber gii/CRUD
'data_delivery_typeList' => $data_delivery_typeList,		// autogeneriert ueber gii/CRUD
'contact_group_as_data_ownerList' => $contact_group_as_data_ownerList,		// autogeneriert ueber gii/CRUD
        
				    ]) ?>

</div>