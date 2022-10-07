<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}


/* @var $this yii\web\View */
/* @var $model app\models\base\ImportStageDbTable */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Import Stage Db Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="import-stage-db-table-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-importstagedbtable')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-importstagedbtable')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : "" ?>
	
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'client_name:ntext',
            'project_name:ntext',
            'db_table_name:ntext',
            'db_table_description:ntext',
            'db_table_field_name:ntext',
            'db_table_field_datatype:ntext',
            'db_table_field_description:ntext',
            'db_table_type_name:ntext',
            'db_table_context_name:ntext',
            'db_table_context_prefix:ntext',
            'isPrimaryKeyField:boolean',
            'isForeignKeyField:boolean',
            'foreignKey_table_name:ntext',
            'foreignKey_table_field_name:ntext',
            '_import_state',
            '_import_date:ntext',
            'is_BusinessKey:boolean',
            'is_GDPR_relevant:boolean',
            'location:ntext',
            'database_or_catalog',
            'schema',
            [
             'label' => Yii::t('app', 'Client'),
             'value' =>              		$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>              	$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name
            ],
            [
             'label' => Yii::t('app', 'Db Database'),
             'value' =>              	$model->fk_db_database_id == "" ? $model->fk_db_database_id : $model->fkDbDatabase->name
            ],
            'column_default_value',
            'column_cant_be_null:boolean',
            'additional_field_1',
            'additional_field_2',
            'additional_field_3',
            'additional_field_4',
            'additional_field_5',
            'additional_field_6',
            'additional_field_7',
            'additional_field_8',
            'additional_field_9',
        ],
    ]) ?>

	
	
	
	
	
    	
    
    
</div>
