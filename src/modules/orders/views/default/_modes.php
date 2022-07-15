<?php
/**
 * Dropdown Modes
 */

use yii\bootstrap\ButtonDropdown;

/* @var $model app\modules\orders\models\OrderSearch */
/* @var $modes array */

$baseLink = [
    'index',
    'status' => $model->status,
    'search_type' => $model->search_type,
    'search' => $model->search,
    'service_id' => $model->service_id,
];

$modesItems = [
    ['label' => Yii::t('orders', 'All'),
        'url' => $baseLink,
    ]
];

foreach ($modes as $modeID => $mode) {
    $modesItems[] = [
        'label' => $mode,
        'url' => array_merge($baseLink, ['mode' => $modeID]),
    ];
}
?>

<?= ButtonDropdown::widget([
    'label' => 'Mode' . (isset($model->mode) ? (': ' . $modes[$model->mode]) : ''),
    'encodeLabel' => false,
    'options' => ['class' => 'btn-th btn-default'],
    'dropdown' => [
        'items' => $modesItems
    ],
]) ?>