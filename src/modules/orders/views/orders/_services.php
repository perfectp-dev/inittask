<?php
/**
 * Dropdown Services
 */

use yii\bootstrap\ButtonDropdown;

/* @var $model orders\models\OrderSearch */
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
        'label' => Yii::t('orders', 'All') . ' (' . $model->allFilteredWithoutServiceOrdersCount . ')',
        'url' => $baseLink,
    ],
];

foreach ($model->servicesWithOrdersCount as $service) {
    $servicesItems[] = [
        'label' => '<span class="label-id">' . $service['orders_cnt'] . '</span> ' . $service['service_name'],
        'url' => array_merge($baseLink, ['service_id' => $service['service_id']])
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