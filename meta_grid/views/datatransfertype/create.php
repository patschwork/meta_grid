<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DataTransferType */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Data Transfer Type')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Transfer Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="data-transfer-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
							]) ?>

</div>