<?php
/**
 * Dropdown Modes
 */

use yii\bootstrap\ButtonDropdown;

/* @var $model orders\models\OrderSearch */

$baseLink = [
    'index',
    'status' => $model->status,
    'search_type' => $model->search_type,
    'search' => $model->search,
    'service_id' => $model->service_id,
];

$modesItems = [
    ['label' => Yii::t('orders', 'search.mode.all'),
        'url' => $baseLink,
    ]
];

foreach ($model->modes as $modeID => $mode) {
    $modesItems[] = [
        'label' => $mode,
        'url' => array_merge($baseLink, ['mode' => $modeID]),
    ];
}
?>

<?= ButtonDropdown::widget([
    'label' => Yii::t('orders', 'search.column.mode') . (isset($model->mode) ? (': ' . $model->modes[$model->mode]) : ''),
    'encodeLabel' => false,
    'options' => ['class' => 'btn-th btn-default'],
    'dropdown' => [
        'items' => $modesItems
    ],
]) ?>