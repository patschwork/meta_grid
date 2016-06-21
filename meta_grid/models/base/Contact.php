<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "contact".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_contact_group_id
 * @property integer $fk_client_id
 * @property string $givenname
 * @property string $surname
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $ldap_cn
 * @property string $description
 *
 * @property Client $fkClient
 * @property ContactGroup $fkContactGroup
 * @property ObjectType $fkObjectType
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'givenname', 'surname', 'email', 'phone', 'mobile', 'ldap_cn', 'description'], 'string'],
            [['fk_object_type_id', 'fk_contact_group_id', 'fk_client_id'], 'integer']
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
            'fk_contact_group_id' => Yii::t('app', 'Fk Contact Group ID'),
            'fk_client_id' => Yii::t('app', 'Fk Client ID'),
            'givenname' => Yii::t('app', 'Givenname'),
            'surname' => Yii::t('app', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'ldap_cn' => Yii::t('app', 'Ldap Cn'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkClient()
    {
        return $this->hasOne(\app\models\Client::className(), ['id' => 'fk_client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkContactGroup()
    {
        return $this->hasOne(\app\models\ContactGroup::className(), ['id' => 'fk_contact_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
