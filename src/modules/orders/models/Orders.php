<?php

namespace app\modules\orders\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $link
 * @property int $quantity
 * @property int $service_id
 * @property int $status 0 - Pending, 1 - In progress, 2 - Completed, 3 - Canceled, 4 - Fail
 * @property int $created_at
 * @property int $mode 0 - Manual, 1 - Auto
 */
class Orders extends \yii\db\ActiveRecord
{
    public static $modesDictionary;
    public static $statusesDictionary;

    public function __construct($config = [])
    {
        parent::__construct($config);

        self::$modesDictionary = [
            0 => Yii::t('orders', 'Manual'),
            1 => Yii::t('orders', 'Auto')
        ];

        self::$statusesDictionary = [
            0 => Yii::t('orders', 'Pending'),
            1 => Yii::t('orders', 'In progress'),
            2 => Yii::t('orders', 'Completed'),
            3 => Yii::t('orders', 'Canceled'),
            4 => Yii::t('orders', 'Error'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'link', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'required'],
            [['user_id', 'quantity', 'service_id', 'status', 'created_at', 'mode'], 'integer'],
            [['link'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('orders', 'ID'),
            'user_id' => Yii::t('orders', 'User'),
            'link' => Yii::t('orders', 'Link'),
            'quantity' => Yii::t('orders', 'Quantity'),
            'service_id' => Yii::t('orders', 'Service'),
            'status' => Yii::t('orders', 'Status'),
            'mode' => Yii::t('orders', 'Mode'),
            'created_at' => Yii::t('orders', 'Created'),
        ];
    }

    public function getService()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    public function getModeName()
    {
        return self::$modesDictionary[$this->mode] ?: Yii::t('orders', 'Unknown');
    }

    public function getStatusName()
    {
        return self::$statusesDictionary[$this->status] ?: Yii::t('orders', 'Unknown');
    }
}
