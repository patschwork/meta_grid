<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DbTableType */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Db Table Type')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
							]) ?>

</div>