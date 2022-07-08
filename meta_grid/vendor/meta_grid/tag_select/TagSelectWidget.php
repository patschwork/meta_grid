<?php 
namespace vendor\meta_grid\tag_select;

use Yii;
use yii\base\Widget;

use dosamigos\selectize\SelectizeTextInput;
use yii\helpers\Url;
use app\models\MapObject2Tag;
use app\models\VTagsOptGroup;
use yii\helpers\ArrayHelper;

class TagSelectWidget extends Widget
{
    public $name = "";
    public $object_id;
    public $object_type_id;
    public $user_id;
    public $project_id;
    
    public function init()
    {
        parent::init();
    }

    private function composeTagSelect($name = "", $ref_fk_object_id, $ref_fk_object_type_id, $fk_user_id, $fk_project_id)
    {
        $modelMapObject2Tag = new MapObject2Tag();
        $result = $modelMapObject2Tag
           ->find()
           ->joinWith('fkTag')
           ->select(['fk_tag_id'])
           ->where(['ref_fk_object_id' => $ref_fk_object_id, 'ref_fk_object_type_id' => $ref_fk_object_type_id])
           ->andWhere(['in', '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)', [$fk_user_id, -1]])
           ->all();
        $values_csv = implode(",",ArrayHelper::map($result, 'fk_tag_id', 'fk_tag_id'));
        
        if ($name === "") $name = 'meta_grid_tag_widget' . "-" . $ref_fk_object_type_id . "-" .  $ref_fk_object_id;
        $ajaxErrorMsg = Yii::t('app', 'Error while asynchronous call!');
        $TagsWidget = SelectizeTextInput::widget([
            'name' => $name,
            'value' => $values_csv,  // id's of table Tag which are currently selected
            'clientOptions' => [
                'plugins' => ['remove_button', 'optgroup_columns'],
                'valueField' => 'id',
                'labelField' => 'name',
                'searchField' => ['name'],
                'create' => true,
                'optgroups' => [
                    ['id' => 0, 'name' => 'Global'],
                    ['id' => 1, 'name' => 'Project'],
                    ['id' => 2, 'name' => 'Personal'],
                    ['id' => -1, 'name' => 'Else']
                ],
                'optgroupField' => 'optgroup',
                'optgroupLabelField' => 'name',
                'optgroupValueField' =>  'id',
                'optgroupOrder' => ['0', '1', '2', '-1'],
                'options' => // all possible tags for scenario
                    VTagsOptGroup::find()
                    ->andWhere(['fk_user_id' => $fk_user_id])
                    ->orWhere(['fk_project_id' => $fk_project_id])
                    ->orWhere(['(CASE WHEN fk_project_id IS NULL THEN -1 ELSE fk_project_id END)' => -1, '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)' => -1])
                    ->all(),
                'render' => [
                            'item' => new \yii\web\JsExpression("function(item, escape) {
                                // console.log(item.id);
                                if (item.optgroup == 2)
                                {
                                    return '<div style=\"background: rgb(129,34,195); background: linear-gradient(175deg, rgba(129,34,195,1) 0%, rgba(64,27,137,1) 100%); color: white;\">' + escape(item.name) + '</div>';
                                }                        
                                else if (item.optgroup == 1)
                                {
                                    return '<div style=\"background: rgb(39,95,232); background: linear-gradient(175deg, rgba(39,95,232,1) 0%, rgba(27,46,137,1) 100%); color: white;\">' + escape(item.name) + '</div>';
                                }
                                else if (item.optgroup == 0)
                                {
                                    return '<div style=\"background: rgb(50,223,34); background: linear-gradient(175deg, rgba(50,223,34,1) 0%, rgba(59,112,23,1) 100%); color: white;\">' + escape(item.name) + '</div>';
                                }
                                else 
                                {
                                    return '<div>' + escape(item.name) + '</div>';
                                }
                            }"),
                ],
                'onChange' => new \yii\web\JsExpression("function(value) {
                    $.ajax({
                        type: 'POST',
                        url: '" . Url::to(['/tag/saveglobal']) . "',
                        data: {
                                 'values'         : value
                                ,'id'             : $ref_fk_object_id
                                ,'object_type_id' : $ref_fk_object_type_id
                              },
                        dataType: 'json',
                        success: function (data) {
                        },
                        error: function (errormessage) {
                            alert('$ajaxErrorMsg');
                        }
                    });
                }")
            ],
        ]);

        return $TagsWidget;
    }

    public function run()
    {
    	parent::run();
    	TagSelectAsset::register($this->getView());
    	
        echo $this->composeTagSelect(
                    $this->name,
                    $this->object_id,
                    $this->object_type_id,
                    $this->user_id,
                    $this->project_id
        );
    }
}
?>