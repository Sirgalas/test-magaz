<?php

namespace backend\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use shop\entities\user\User;

/**
 * @property integer id
 * @property string created_at
 * @property string updated_at
 * @property string username
 * @property string email
 */
class UserSearch extends Model
{
    public $id;
    public $date_to;
    public $date_from;
    public $updated_at;
    public $username;
    public $email;
    public $status;
    public $created_at;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at'], 'integer'],
            [['username',  'email', ], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d']
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params):ActiveDataProvider
    {
        $query = User::find();



        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,

        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
