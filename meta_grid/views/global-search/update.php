<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VAllObjectsUnion */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Vall Objects Union',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vall Objects Unions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'fk_object_type_id' => $model->fk_object_type_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vall-objects-union-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'clientList' => $clientList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
        
				    ]) ?>

</div>