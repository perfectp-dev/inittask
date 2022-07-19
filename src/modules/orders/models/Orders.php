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
            self::MODE_MANUAL => Yii::t('orders', 'search.mode.manual'),
            self::MODE_AUTO => Yii::t('orders', 'search.mode.auto')
        ];

        self::$statusesDictionary = [
            self::STATUS_PENDING => Yii::t('orders', 'search.status.pending'),
            self::STATUS_IN_PROGRESS => Yii::t('orders', 'search.status.inprogress'),
            self::STATUS_COMPLETED => Yii::t('orders', 'search.status.completed'),
            self::STATUS_CANCELED => Yii::t('orders', 'search.status.canceled'),
            self::STATUS_ERROR => Yii::t('orders', 'search.status.error'),
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
            'id' => Yii::t('orders', 'search.column.id'),
            'user_id' => Yii::t('orders', 'search.column.user'),
            'link' => Yii::t('orders', 'search.column.link'),
            'quantity' => Yii::t('orders', 'search.column.quantity'),
            'service_id' => Yii::t('orders', 'search.column.service'),
            'status' => Yii::t('orders', 'search.column.status'),
            'mode' => Yii::t('orders', 'search.column.mode'),
            'created_at' => Yii::t('orders', 'search.column.created'),
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
     * Modes name from dictionary
     * @return string
     */
    public function getModeName()
    {
        return self::$modesDictionary[$this->mode] ?: Yii::t('orders', 'search.mode.unknown');
    }

    /**
     * Statuses name from dictionary
     * @return string
     */
    public function getStatusName()
    {
        return self::$statusesDictionary[$this->status] ?: Yii::t('orders', 'search.status.unknown');
    }
}
