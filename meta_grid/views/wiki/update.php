<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Wiki */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('app', 'Wiki')]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wikis'), 'url' => ['index']];
            // $bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
            $bc = null;
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wiki-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 
       'object_typeList'                => $object_typeList,		// autogeneriert ueber gii/CRUD
        'projectList'                   => $projectList,		// autogeneriert ueber gii/CRUD
        'userList'                      => $userList,		// autogeneriert ueber gii/CRUD
        'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
		'projectListDisables'           => $projectListDisables,
        'mode'                          => $mode,	
    ]) ?>

</div>