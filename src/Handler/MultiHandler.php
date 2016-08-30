<?php

namespace Jugger\Form\Handler;

use Jugger\Form\Handler;

/**
 * Множественный обработчик
 * позволяет указать несколько обработчиков и работать с ними, как с одним
 */
class MultiHandler implements Handler
{
    public $handlers = [];
    
    public function add(Handler $item, $prepend = false) {
        if ($prepend) {
            array_unshift($this->handlers, $item);
        }
        else {
            array_push($this->handlers, $item);
        }
    }

    public function process(array $attributes) {
        foreach ($this->handlers as $handler) {
            $result = $handler->process($attributes);
            if ($result !== true) {
                return $result;
            }
        }
        return true;
    }
}