<?php
namespace mongoex\rest;

use \yii\rest\UrlRule as BaseUrlRule;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class UrlRule extends BaseUrlRule
{
    public $tokens = [
        '{id}' => '<id:\\w[\\w,]*>'
    ];
}