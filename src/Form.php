<?php

namespace Jugger\Form;

use Jugger\Validator\Validator;

/**
 * Description of Form
 *
 * @author Ilya Rupasov <i.rpsv@live.com>
 */
class Form
{
    public $id;
    /**
     * список атрибутов
     * @var Attribute[]
     */
    protected $attributes;
    /**
     * Обработчик
     * @var Handler
     */
    protected $handler;
    /**
     * Список ошибок формы (полей и обработчика)
     * @var array
     */
    protected $errors = [];
    /**
     * Создане формы
     * @param array $attributes список атрибутов формы
     */
    public function __construct(array $attributes) {
        $this->attributes = [];
        foreach ($attributes as $data) {
            $this->addAttribute($data);
        }
    }
    
    public function addAttribute(array $data) {
        $attribute = new Attribute($data['name']);
        $attribute->hint = $data['hint'] ?: null;
        if (!$data['validators']) {
            return;
        }
        foreach ($data['validators'] as $validator) {
            if ($validator instanceof Validator) {
                $attribute->addValidator($validator);
            }
            else {
                throw new Exception("Параметр 'validator' должен быть списков с экземплярами потомков класса 'Validator'");
            }
        }
        $this->attributes[$attribute->name] = $attribute;
    }
    /**
     * Заполнение формы
     * @param array $fields список атрибутов
     * @return boolean TRUE - если хотя бы один атрибут был загружен из входных данных
     */
    public function load($fields) {
        if (is_null($fields) || !is_array($fields)) {
            return false;
        }
        $isFilling = false;
        foreach ($fields as $name => $value) {
            if (!array_key_exists($name, $this->attributes)) {
                continue;
            }
            $this->attributes[$name]->value = $value;
            if (!$isFilling) {
                $isFilling = true;
            }
        }
        return $isFilling;
    }
    /**
     * Валидация формы
     * @return boolean
     */
    public function validate() {
        /* @var $attribute Attribute */
        foreach ($this->attributes as $attribute) {
            if ($attribute->error) {
                $this->errors[$attribute->name] = $attribute->error;
            }
        }
        return empty($this->errors);
    }
    /**
     * Обработка формы
     * @return boolean
     */
    public function handler() {
        $attributes = $this->getAttributes();
        $result = $this->handler->process($attributes);
        if ($result !== true) {
            $this->errors[] = $result;
            $result = false;
        }
        return $result;
    }
}