<?php

namespace app\modules\orders\models;

use Yii;
use yii\db\ActiveRecord;

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
class Orders extends ActiveRecord
{
    public static $modesDictionary;
    public static $statusesDictionary;

    const MODE_MANUAL = 0;
    const MODE_AUTO = 1;

    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_ERROR = 4;

    /**
     * Constructor. Initialize modes and statuses dictionaries
     * @param $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        self::$modesDictionary = [
            self::MODE_MANUAL => Yii::t('orders', 'Manual'),
            self::MODE_AUTO => Yii::t('orders', 'Auto')
        ];

        self::$statusesDictionary = [
            self::STATUS_PENDING => Yii::t('orders', 'Pending'),
            self::STATUS_IN_PROGRESS => Yii::t('orders', 'In progress'),
            self::STATUS_COMPLETED => Yii::t('orders', 'Completed'),
            self::STATUS_CANCELED => Yii::t('orders', 'Canceled'),
            self::STATUS_ERROR => Yii::t('orders', 'Error'),
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

    /**
     * Related Service
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Services::class, ['id' => 'service_id']);
    }

    /**
     * Related user
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * Full username of related user
     * @return string
     */
    public function getUserFullName()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    /**
     * Modes name from dictionary
     * @return string
     */
    public function getModeName()
    {
        return self::$modesDictionary[$this->mode] ?: Yii::t('orders', 'Unknown');
    }

    /**
     * Statuses name from dictionary
     * @return string
     */
    public function getStatusName()
    {
        return self::$statusesDictionary[$this->status] ?: Yii::t('orders', 'Unknown');
    }
}
