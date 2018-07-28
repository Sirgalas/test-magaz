<?php

namespace shop\entities\user;

use shop\entities\user\User;
use Yii;
use Webmozart\Assert\Assert;
/**
 * This is the model class for table "user_networks".
 *
 * @property int $id
 * @property int $user_id
 * @property string $identity
 * @property string $network
 *
 * @property User $user
 */
class UserNetworks extends \yii\db\ActiveRecord
{

    public static function create($network, $identity){
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $item= new static();
        $item->network=$network;
        $item->identity=$identity;

    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_networks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'identity', 'network'], 'required'],
            [['user_id'], 'integer'],
            [['identity'], 'string', 'max' => 255],
            [['network'], 'string', 'max' => 16],
            [['identity', 'network'], 'unique', 'targetAttribute' => ['identity', 'network']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'identity' => 'Identity',
            'network' => 'Network',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function isFor($network, $identity):bool
    {
        return $this->network === $network && $this->identity === $identity;
    }
}

