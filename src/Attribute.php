<?php

namespace Jugger\Form;

/**
 * Атрибут (поле) формы
 * Содержит информацию о поле, а также список валидаторов поля
 */
class Attribute
{
    public $name;
    public $value;
    public $hint;
    public $errors = [];
    
    protected $validators;

    public function validate() {
        
    }
}