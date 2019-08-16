<?php

namespace app\models\base;

/**
 * This is the ActiveQuery class for [[Bracket]].
 *
 * @see Bracket
 */
class BracketQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Bracket[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Bracket|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
