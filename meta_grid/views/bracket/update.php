<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Bracket',
]) . ' ' . $modelBracket->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelBracket->name, 'url' => ['view', 'id' => $modelBracket->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bracket-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelBracket' => $modelBracket,
       	'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
		'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'attributeList' => $attributeList,		// autogeneriert ueber gii/CRUD
		'object_type_as_searchFilterList' => $object_type_as_searchFilterList,		// autogeneriert ueber gii/CRUD
    	'modelsBracketSearchPattern' => $modelsBracketSearchPattern,        
    ]) ?>

</div>
