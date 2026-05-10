<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wiki".
 *
 * @property int|null $id
 * @property string|null $uuid
 * @property int|null $fk_object_type_id
 * @property string $name
 * @property string|null $description
 * @property int|null $fk_project_id
 * @property int|null $fk_user_id
 *
 * @property ObjectType $fkObjectType
 * @property Project $fkProject
 */
class Wiki extends \app\models\base\Wiki
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['uuid'], 'string'],
            [['fk_object_type_id'], 'default', 'value' => 31],
            // [['fk_user_id'], 'default', 'value' => 0],
            [['uuid', 'description'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_user_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
            [['name', 'fk_project_id', 'fk_user_id'], 'unique', 'targetAttribute' => ['name', 'fk_project_id', 'fk_user_id']],
            // [['fk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user_id' => 'id']],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['fk_project_id' => 'id']],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::class, 'targetAttribute' => ['fk_object_type_id' => 'id']],
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

        // no special handling for admins!
        $obj->andWhere([
                'in','(CASE WHEN fk_project_id IS NULL THEN -1 ELSE fk_project_id END)', $permProjectsCanSee 
        ]);
        $obj->andWhere([
                'in', '(CASE WHEN fk_user_id IS NULL THEN -1 ELSE fk_user_id END)', [Yii::$app->user->id, -1]
        ]);
		return $obj;
    }
    
    public static function findOne($condition)
	{
		$model=static::findByCondition($condition)->one();
		if ( (isset($model)) ) // no special handling for admins!
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
