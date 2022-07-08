<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property integer $fk_project_id
 * @property integer $fk_user_id
 *
 * @property MapObject2Tag[] $mapObject2Tags
 * @property User $fkUser
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Tag extends \app\models\base\Tag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_user_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
            [['name', 'fk_project_id', 'fk_user_id'], 'unique', 'targetAttribute' => ['name', 'fk_project_id', 'fk_user_id'], 'message' => 'The combination of Name, Fk Project ID and Fk User ID has already been taken.'],
            // [['fk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['fk_user_id' => 'id']],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['fk_project_id' => 'id']],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['fk_object_type_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkUser()
    {
        return $this->hasOne(\vendor\meta_grid\user_model\User::className(), ['id' => 'fk_user_id']);
    }

	public static function find()
	{
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
        array_push($permProjectsCanSee, -1);
		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
		if (!Yii::$app->User->identity->isAdmin)
		{
			$obj->andWhere([
					'in','(CASE WHEN fk_project_id IS NULL THEN -1 ELSE fk_project_id END)', $permProjectsCanSee 
            ]);
            $obj->andWhere([
                    'in', '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)', [Yii::$app->user->id, -1]
            ]);
		}
		return $obj;
    }
    
    public static function findOne($condition)
	{
		$model=static::findByCondition($condition)->one();
		if ( (isset($model)) || (Yii::$app->User->identity->isAdmin))
		{
			return $model;
		}
		else
		{
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission for this data.'));
			return null;
		}
	}
}
