<?php
namespace mongoex\validators;

use yii\validators\Validator;
use yii\base\InvalidConfigException;
use yii\base\DynamicModel;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class MongoObjectValidator extends Validator
{
    public $fields;
    
    public function init()
    {
        if ($this->fields === null) {
            throw new InvalidConfigException(Yii::t('app', 'rules[] parameter is required for MongoObjectValidator'));
        }
    }
    
    public function validateAttribute($model, $attribute)
    {
        $this->validateObject($model, $attribute, $model->{$attribute});
    }
    
    protected function validateObject($model, $attribute, array $values, $index = null)
    {
        $values = $this->refillValues($values);

        $dynamic = DynamicModel::validateData($values, $this->fields);
        if ($dynamic->hasErrors()) {
            foreach ($dynamic->getErrors() as $attr => $error) {
                $attrName = $attribute . '.' . ($index ? $index . '.' : null) . $attr;
                $model->addError($attrName, $error[0]);
            }
        }
    }
    
    protected function refillValues(array $values)
    {
        $attributes = [];
        foreach ($this->fields as $rule) {
            if (is_array($rule[0])) {
                foreach ($rule[0] as $attribute) {
                    $attributes[$attribute] = null;
                }
            }
            else {
                $attributes[$rule[0]] = null;
            }
        }
        
        return array_merge($attributes, $values);
    }
}