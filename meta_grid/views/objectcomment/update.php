<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objectcomment */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Objectcomment',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Objectcomments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="objectcomment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
        
				    ]) ?>

</div>