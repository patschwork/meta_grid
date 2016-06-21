<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tool;

/**
 * ToolSearch represents the model behind the search form about `app\models\Tool`.
 */
class ToolSearch extends Tool
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_tool_type_id'], 'integer'],
            [['uuid', 'tool_name', 'vendor', 'version', 'comment'], 'safe'],
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
        $query = Tool::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fk_tool_type_id' => $this->fk_tool_type_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'tool_name', $this->tool_name])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
