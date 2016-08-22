<?php

namespace Jugger\Form;

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

    public function load(array $fields) {
        
    }
    
    public function validate() {
        
    }
    
    public function process() {
        $attributes = $this->getAttributes();
        $result = $this->handler->process($attributes);
        if ($result !== true) {
            $this->errors[] = $result;
            $result = false;
        }
        return $result;
    }
}