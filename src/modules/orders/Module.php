<?php

namespace orders;

use yii\base\Module as BaseModule;

/**
 * orders module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc
     */
    public $controllerNamespace = 'orders\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
