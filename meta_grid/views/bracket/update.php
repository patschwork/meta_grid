<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Bracket')]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bracket-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'attributeList' => $attributeList,		// autogeneriert ueber gii/CRUD
'object_type_as_searchFilterList' => $object_type_as_searchFilterList,		// autogeneriert ueber gii/CRUD
        
					'modelsBracketSearchPattern' => isset($modelsBracketSearchPattern) ? $modelsBracketSearchPattern : null,
		    ]) ?>

</div>