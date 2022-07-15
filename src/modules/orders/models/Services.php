<?php

namespace app\modules\orders\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 */
class Services extends \yii\db\ActiveRecord
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

    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['service_id' => 'id']);
    }

    public function getOrdersCount()
    {
        return $this->hasMany(Orders::className(), ['service_id' => 'id'])->count();
    }
}
