<?php

namespace Jugger\Form\Handler;

use Jugger\Form\Handler;
use Bitrix\Main\Mail\Event;

class EmailEventHandler implements Handler
{
    public $eventType;
    public $matching;
    
    public function __construct(array $params) {
        $this->eventType = $params['id'];
        $this->matching = $params['matching'];
    }
    
    public function process(array $attributes) {
        $fields = [];
        foreach ($this->matching as $formName => $eventName) {
            $fields[$eventName] = $attributes[$formName];
        }
        $result = Event::send([
            "EVENT_NAME" => $this->eventType,
            "LID" => SITE_ID,
            "C_FIELDS" => $fields,
        ]);
        if ($result->isSuccess()) {
            return true;
        }
        else {
            return implode("<br>", $result->getErrorMessages());
        }
    }
}