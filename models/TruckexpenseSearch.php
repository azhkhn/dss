<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Truckexpense;

/**
 * TruckexpenseSearch represents the model behind the search form of `app\models\Truckexpense`.
 */
class TruckexpenseSearch extends Truckexpense
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'truck_id'], 'integer'],
            [['date_reported', 'display_date', 'spare_part_service', 'series_no', 'reason', 'warranty', 'remark'], 'safe'],
            [['cost'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params, $truck = null,$month = null)
    {
        $query = Truckexpense::find()->where(['DATE_FORMAT(display_date,"%Y-%m")' => $month, 'truck_id'=>$truck])->orderBy(['display_date'=>'asc','id'=>'asc']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_reported' => $this->date_reported,
            'display_date' => $this->display_date,
            'cost' => $this->cost,
            'truck_id' => $this->truck_id,
        ]);

        $query->andFilterWhere(['like', 'spare_part_service', $this->spare_part_service])
            ->andFilterWhere(['like', 'series_no', $this->series_no])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'warranty', $this->warranty])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
