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
    public $created_at;
    public $updated_at;
    public $username;
    public $email;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at','status'], 'integer'],
            [['username',  'email', ], 'safe'],
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
            'created_at' => $this->created_at,

        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
