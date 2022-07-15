<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\orders\models\OrderSearch */
/* @var $statuses array */

$baseLink = [
    'index',
    'search_type' => $model->search_type,
    'search' => $model->search,
];
?>

    <li<?= (!isset($model->status) ? ' class="active"' : '') ?>>
        <?= Html::a(Yii::t('orders', 'All orders'), $baseLink) ?>
    </li>

<?php foreach ($statuses as $statusID => $status) { ?>
    <li<?= (isset($model->status) && $model->status == $statusID ? ' class="active"' : '') ?>>
        <?= Html::a($status, array_merge($baseLink, ['status' => $statusID])) ?>
    </li>
<?php } ?>