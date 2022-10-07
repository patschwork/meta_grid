<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "VTag2ObjectList".
 */
class VTag2ObjectList extends \app\models\base\VTag2ObjectList
{
	public static function primaryKey()
    {
        return array('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'fk_tag_id']);
    }


	public static function find()
	{
    	$permObjectTypesCanView = Yii::$app->User->identity->permObjectTypesCanViewByUserId;
        $permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
        array_push($permProjectsCanSee, -1);

		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);

        $obj->Where([
                'in','controllername', $permObjectTypesCanView
        ]);

        $obj->andWhere([
            'in','(CASE WHEN fk_project_id IS NULL THEN -1 ELSE fk_project_id_from_object END)', $permProjectsCanSee 
        ]);
        $obj->andWhere([
                'in', '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)', [Yii::$app->user->id, -1]
        ]);
		return $obj;
	}
}