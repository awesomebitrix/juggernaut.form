<?php

namespace Jugger\Form;

use Jugger\Validator\Validator;

/**
 * Атрибут (поле) формы
 * Содержит информацию о поле, а также список валидаторов поля
 */
class Attribute
{
    /**
     * Имя
     * @var string
     */
    public $name;
    /**
     * Значение
     * @var mixed
     */
    public $value;
    /**
     * Подсказка (описание)
     * @var string
     */
    public $hint;
    /**
     * Ошибка валидации
     * Даже при нескольких валидаторах, ошибка все равно будет одна
     * в нее записывается первая ошибка, и дальнейшая проверка не производится.
     * Реализовано таким образом, т.к. дальнейшая проверка не имеет смысла,
     * если уже данные невалидные
     * @var string
     */
    public $error;
    /**
     * Список валидаторов поля
     * @var \Jugger\Validator\Validator[]
     */
    public $validators = [];
    /**
     * Конструктор
     * @param array $config список свойств атрибута
     */
    public function __construct(array $config) {
        extract($config);
        if (isset($name)) {
            $this->name = $name;
        }
        else {
            throw new Exception("Необходимо указать 'name' атрибута");
        }
        if (isset($hint)) {
            $this->hint = $hint;
        }
        if (!isset($validators)) {
            return;
        }
        foreach ($validators as $validator) {
            if ($validator instanceof Validator) {
                $this->addValidator($validator);
            }
            else {
                throw new Exception("Параметр 'validator' должен быть списков с экземплярами потомков класса 'Jugger\Validator\Validator'");
            }
        }
    }
    /**
     * Валидация значения
     * @return boolean TRUE - если данные валидны, FALSE - иначе
     */
    public function validate() {
        /* @var $validator \Jugger\Validator\Validator */
        $this->error = false;
        foreach ($this->validators as $validator) {
            if ($validator->validate($this->value) !== true) {
                $this->error = get_class($validator);
                break;
            }
        }
        return $this->error === false;
    }
    /**
     * Добавляет валидатор
     * @param Validator $validator валидатор
     * @param boolean $prepend флаг, определяющий добавление в начало списка
     */
    public function addValidator(Validator $validator, $prepend = false) {
        if ($prepend) {
            array_unshift($this->validators, $validator);
        }
        else {
            array_push($this->validators, $validator);
        }
    }
    
    public function addValidators(array $validators) {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }
}