<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Bracket',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'modelBracket' => $modelBracket,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
		'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'attributeList' => $attributeList,		// autogeneriert ueber gii/CRUD
		'object_type_as_searchFilterList' => $object_type_as_searchFilterList,		// autogeneriert ueber gii/CRUD
		'modelsBracketSearchPattern' => $modelsBracketSearchPattern,        
    ]) ?>

</div>
