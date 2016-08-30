<?php

namespace Jugger\Form\Handler;

use Bitrix\Main\Loader;
use Jugger\Form\Handler;

class IblockElementHandler implements Handler
{
    public $defaultFields;
    
    public function __construct(array $params) {
        $this->defaultFields = $params;
    }
    
    public function process(array $attributes) {
        $fields = $this->defaultFields;
        $props = [];
        foreach ($attributes as $name => $attr) {
            $value = $attr->value;
            if (is_numeric($value)) {
                $props[$name] = $value;
            }
            else {
                $fields[$name] = $value;
            }
        }
        return $this->addElement($fields, $props);
    }

    protected function addElement($fields, $props) {
        Loader::includeModule("iblock");
        $ibe = new \CIBlockElement();
        if ($elementId = $ibe->Add($fields)) {
            $iblockId = $fields['IBLOCK_ID'];
            $ibe->SetPropertyValues($elementId, $iblockId, $props);
            return true;
        }
        return false;
    }
}