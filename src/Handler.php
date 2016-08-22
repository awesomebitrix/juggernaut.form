<?php

namespace Jugger\Form;

/**
 * Обработчик формы
 * Вызывается после загрузки и валидации формы
 */
interface Handler
{
    /**
     * Обработка результатов формы
     * 
     * @param array $attributes список атрибутов в формате: ["name" => "value"]
     */
    public function process(array $attributes);
}