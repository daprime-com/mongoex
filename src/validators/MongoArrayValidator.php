<?php
namespace mongoex\validators;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class MongoArrayValidator extends MongoObjectValidator
{
    public function validateAttribute($model, $attribute)
    {
        $values = $model->{$attribute};
        foreach ($values as $index => $value) {
            $this->validateObject($model, $attribute, $value, $index);
        }
    }
}