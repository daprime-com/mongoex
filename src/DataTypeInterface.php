<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
interface DataTypeInterface
{
    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_INTEGER = 'integer';
    const DATA_TYPE_DOUBLE = 'double';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_OBJECT = 'object';
    const DATA_TYPE_ARRAY = 'array';
}
