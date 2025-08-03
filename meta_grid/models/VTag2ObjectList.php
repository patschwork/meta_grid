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
        $permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
        array_push($permProjectsCanSee, -1);

		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);

        $obj->Where([
                'in','controllername', $permObjectTypesCanView
        ]);

        // old (see T386)
        // $obj->andWhere([
        //     'in','(CASE WHEN fk_project_id IS NULL THEN -1 ELSE fk_project_id_from_object END)', $permProjectsCanSee 
        // ]);
        // $obj->andWhere([
        //         'in', '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)', [Yii::$app->user->id, -1]
        // ]);

        // new (see T386)
        $obj->andWhere(['and',
        ['optgroup' => 0],
        ['in', 'fk_project_id_from_object', $permProjectsCanSee]
        ]);
        $obj->orWhere(['and',
            ['optgroup' => 1],
            ['in', 'fk_project_id', $permProjectsCanSee]

        ]);
        $obj->orWhere(['and',
            ['optgroup' => 2],
            ['fk_user_id' => Yii::$app->user->id]
        ]);        

        // this case must not exist! But when, then catch this here
        // we'll use the user permission as fallback
        $obj->orWhere(['and',
            ['optgroup' => 1],
            ['fk_user_id' => Yii::$app->user->id]
        ]);

        // T387:
        $obj->orWhere(['and',
            ['optgroup' => 0],
            ['in', 'fk_client_id_from_object', $permClientsCanSee]
        ]);

        // $sql = $obj->createCommand()->getRawSql();

		return $obj;
	}
}