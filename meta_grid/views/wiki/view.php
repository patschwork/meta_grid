<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\data\ActiveDataProvider;

use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Wiki */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wikis'), 'url' => ['index']];
    // $bc = (new \vendor\meta_grid\helper\Utils())->breadcrumb_project_or_client($model);
    $bc = null;
if (!is_null($bc)) $this->params['breadcrumbs'][] = $bc;
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="wiki-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-wiki')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('delete-wiki')  ? Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) : "" ?>
	
    </p>
<?php
    $TagsWidget = \vendor\meta_grid\tag_select\TagSelectWidget::widget(
        [
            'object_id' => $model->id,
            'object_type_id' => $model->fk_object_type_id,
            // 'user_id' => \Yii::$app->getUser()->id,
            // 'project_id' => $model->fkProject->id
            'user_id' => null,
            'project_id' => null
        ]);
?>	

    <?php
		$mode = "personal";
		if ($model->fk_project_id !== null)
		{
			$mode = "project";
            try
            {
                $project_name = $model->fkProject->name;
            } catch (\Exception $e) 
            {
                $project_name = "";
            }    
            $visibility_info = Yii::t('app', 'This wiki page can be seen by all member who have access to this project ({project_name})', ['project_name' => $project_name]);
		}
		if ($model->fk_user_id !== null)
		{
			$mode = "personal";
            $visibility_info = Yii::t('app', 'This wiki page can only be seen by you');
		}

		if ($model->fk_project_id === null && $model->fk_user_id === null)
		{
			$mode = "global";
            $visibility_info = Yii::t('app', 'This wiki page can seen by all user who can login to Meta#Grid');
		}
    ?>	

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
             'label' => Yii::t('app', 'Tags'),
             'value' => $TagsWidget,
             'format' => 'raw',
            ],
            'id',
            // 'uuid:ntext',
            // 'fk_object_type_id',
            // 'name:ntext',
            // 'description:html',
            // [
            //  'label' => Yii::t('app', 'Client'),
            //  'value' =>              		    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject->fkClient === NULL ? Yii::t('app', "Can't lookup the client name (for project {fk_project_id})", ['fk_project_id' => $model->fk_project_id]) : $model->fkProject->fkClient->name)
            // ],
            // [
            //  'label' => Yii::t('app', 'Project'),
            //  'value' =>         	    $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkProject', 'this_id' => $model->fk_project_id]) : $model->fkProject->name)
            // ],
            // [
            //  'label' => Yii::t('app', 'User'),
            //  'value' =>         	    $model->fk_user_id == "" ? $model->fk_user_id : ($model->fkUser === NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkUser', 'this_id' => $model->fk_user_id]) : $model->fkUser->name)
            // ],
            [
             'label' => Yii::t('app', 'Visibility for this wiki page'),
             'value' =>      $visibility_info
            ],
        ],
    ]) ?>

        <?php
                use yii\web\JsExpression;

                echo \floor12\summernote\Summernote::widget([
                'name' => 'some_field',
                'value' => $model->description,
                'options' => ['id' => 'summernote-readonly'],
                ]);

                $this->registerCss("
                    /* Toolbar ausblenden */
                    .note-editor .note-toolbar{display:none !important;}

                    /* Editor-Hintergrund weiß */
                    .note-editor .note-editable { background-color: #ffffff !important; }

                    /* Falls das eigentliche Textarea sichtbar ist */
                    #summernote-readonly { background-color: #ffffff !important; }

                    /* Höhe anpassen zu 500px */
                    .note-editor .note-editable { height: 100% !important; min-height: 500px !important; }
                ");


                $this->registerJs("
                /* Toolbar ausblenden */
                $('#summernote-readonly').closest('.note-editor').find('.note-toolbar').remove();

                /* Editor read-only */
                $('#summernote-readonly').summernote('disable');
                ");

        ?>
        
	    <br/>
        <br/>
    	<?php
	
			
			// {... Kommentierung pro Object
		// 		autogeneriert ueber gii/CRUD
		$objectcommentSearchModel = new app\models\ObjectcommentSearch();
        
        $query = app\models\Objectcomment::find();
        $objectcommentDataProvider = new ActiveDataProvider([
        		'query' => $query,
				'pagination' => false,
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $model->id,
        		'ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
				'pagination' => false,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $model->id,
        		'filter_ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);

		     
        
		echo Tabs::widget([
			'items' => 
			[
				[
					'label' => Yii::t('app', 'Comments'),
					'content' => $this->render('../objectcomment/_index_external', [
								    'searchModel' => $objectcommentSearchModel,
            						'dataProvider' => $objectcommentDataProvider,
								    ]),
					'active' => true,
					'options' => ['id' => 'tabComments']  // important for shortcut
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapper/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false,
					'options' => ['id' => 'tabMapping']  // important for shortcut
				],
						],
		]);
		// Kommentierung pro Object ...}
	
	    		
	?>  
    
    
</div>
