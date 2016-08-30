<?php

namespace Jugger\Form;

/**
 * Description of Form
 *
 * @author Ilya Rupasov <i.rpsv@live.com>
 */
class Form
{
    /**
     * Идентификатор формы
     * при отправке данных все поля записываются в контейнер с ID формы
     * @var string
     */
    public $id;
    /**
     * список атрибутов
     * @var Attribute[]
     */
    public $attributes;
    /**
     * Обработчик
     * @var Handler
     */
    public $handler;
    /**
     * Список ошибок формы (полей и обработчика)
     * @var array
     */
    public $errors = [];
    /**
     * Создане формы
     * @param array $attributes список атрибутов формы
     */
    public function __construct(array $attributes = []) {
        $this->attributes = [];
        foreach ($attributes as $data) {
            $this->addAttribute($data);
        }
    }
    /**
     * Добавление атрибута к форму
     * @param mixed $attribute экземпляр атрибута, или конфигурация для создания атрибута
     * @throws Exception
     */
    public function addAttribute($attribute) {
        if ($attribute instanceof Attribute) {
            // pass
        }
        elseif (!is_array($attribute)) {
            throw new Exception("Параметр 'data' должен быть экземпляром класса 'Jugger\Form\Attribute' или массивом конфигурации данного класса");
        }
        else {
            $attribute = new Attribute($attribute);
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
            if ($attribute->validate() === false) {
                $this->errors[$attribute->name] = $attribute->error;
            }
        }
        return empty($this->errors);
    }
    /**
     * Обработка формы
     * @return boolean
     */
    public function handle() {
        $attributes = $this->getAttributes();
        $result = $this->handler->process($attributes);
        if ($result !== true) {
            $this->errors[] = $result;
            $result = false;
        }
        return $result;
    }
    /**
     * Последовательная загрузка, валидация и обработка
     * @param array $fields
     * @return boolean
     */
    public function process($fields) {
        return $this->load($fields) && $this->validate() && $this->handle();
    }
    
    public function getErrors() {
        return $this->errors;
    }

    public function getAttributes() {
        return $this->attributes;
    }
}