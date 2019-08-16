<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VObjectcommentSearchinterface;

/**
 * ObjectcommentSearch represents the model behind the search form about `VObjectcommentSearchinterface`.
 */
class ObjectcommentSearch extends VObjectcommentSearchinterface 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'ref_fk_object_id', 'ref_fk_object_type_id'], 'integer'],
            [['uuid', 'comment', 'created_at_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = VObjectcommentSearchinterface::find();        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			        'pagination' => [
						'pageSize' => 100,
					]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fk_object_type_id' => $this->fk_object_type_id,
            'ref_fk_object_id' => $this->ref_fk_object_id,
            'ref_fk_object_type_id' => $this->ref_fk_object_type_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', '%'.$this->uuid.'%', false])
            ->andFilterWhere(['like', 'comment', '%'.$this->comment.'%', false])
            ->andFilterWhere(['like', 'created_at_datetime', '%'.$this->created_at_datetime.'%', false]);

        return $dataProvider;
    }
}
