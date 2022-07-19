<?php
/**
 * Dropdown Services
 */

use yii\bootstrap\ButtonDropdown;

/* @var $model app\modules\orders\models\OrderSearch */
/* @var $services array */
/* @var $allOrdersCount int */

$baseLink = [
    'index',
    'status' => $model->status,
    'search_type' => $model->search_type,
    'search' => $model->search,
    'mode' => $model->mode,
];

$servicesItems = [
    [
        'label' => '<span class="label-id">' . $allOrdersCount . '</span> ' .
            Yii::t('orders', 'All'),
        'url' => $baseLink,
    ],
];

foreach ($services as $service) {
    $servicesItems[] = [
        'label' => '<span class="label-id">' . $service['orders_cnt'] . '</span> ' . $service['name'],
        'url' => array_merge($baseLink, ['service_id' => $service['id']])
    ];
}
?>
<?= ButtonDropdown::widget([
    'label' => Yii::t('orders', 'search.column.service') . (isset($model->service_id) ? (': ' . $model->service->name) : ''),
    'options' => ['class' => 'btn-th btn-default'],
    'dropdown' => [
        'items' => $servicesItems,
        'encodeLabels' => false,
    ],
]); ?>