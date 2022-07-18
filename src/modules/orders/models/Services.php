<?php

namespace app\modules\orders\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 */
class Services extends ActiveRecord
{
    public $orders_cnt;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('orders', 'ID'),
            'name' => Yii::t('orders', 'Name'),
        ];
    }

    /**
     * Related orders
     * @return ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['service_id' => 'id']);
    }

    /**
     * Related orders counter
     * @return int
     */
    public function getOrdersCount()
    {
        return $this->hasMany(Orders::class, ['service_id' => 'id'])->count();
    }
}
