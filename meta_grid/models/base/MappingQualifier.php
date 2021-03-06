<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "mapping_qualifier".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $short_name
 * @property string $description
 * @property boolean $needs_object_depends_on
 *
 * @property MapObject2Object[] $mapObject2Objects
 * @property ObjectType $fkObjectType
 */
class MappingQualifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mapping_qualifier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id'], 'integer'],
            [['needs_object_depends_on'], 'boolean'],
            [['name', 'short_name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['fk_object_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'fk_object_type_id' => Yii::t('app', 'Fk Object Type ID'),
            'name' => Yii::t('app', 'Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'description' => Yii::t('app', 'Description'),
            'needs_object_depends_on' => Yii::t('app', 'Needs Object Depends On'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects()
    {
        return $this->hasMany(MapObject2Object::className(), ['fk_mapping_qualifier_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
