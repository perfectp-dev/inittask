<?php

use yii\helpers\Html;
use app\modules\orders\models\Orders;

/* @var $this yii\web\View */
/* @var $model app\modules\orders\models\OrderSearch */
$statuses = [
    0 => Yii::t('orders', 'Pending'),
    1 => Yii::t('orders', 'In progress'),
    2 => Yii::t('orders', 'Completed'),
    3 => Yii::t('orders', 'Canceled'),
    4 => Yii::t('orders', 'Fail'),
];
?>

    <li<?= (!isset($model->status) ? ' class="active"' : '') ?>>
        <?= Html::a(Yii::t('orders', 'All orders'), [
            'index',
            'search' => $model->search,
            'search_type' => $model->search_type,
        ]) ?>
    </li>

<?php
foreach (Orders::statuses as $statusID => $status) { ?>
    <li<?= (isset($model->status) && $model->status == $statusID ? ' class="active"' : '') ?>>
        <?= Html::a($status, [
            'index',
            'status' => $statusID,
            'search' => $model->search,
            'search_type' => $model->search_type,
        ]) ?>
    </li>
<?php } ?>