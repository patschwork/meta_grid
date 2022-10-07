<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Bracket')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'attributeList' => $attributeList,		// autogeneriert ueber gii/CRUD
'object_type_as_searchFilterList' => $object_type_as_searchFilterList,		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $object_persistence_methodList,		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $datamanagement_processList,		// autogeneriert ueber gii/CRUD
					'modelsBracketSearchPattern' => isset($modelsBracketSearchPattern) ? $modelsBracketSearchPattern : null,
				'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
	]) ?>

</div>