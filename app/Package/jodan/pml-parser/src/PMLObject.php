<?php

namespace Jodan\PMLParser;
use SimpleXMLElement;

class PMLObject
{
    private $order = [];

    private $pizza = [];

    private $toppings = [];

    /**
     * Create PMLObject
     * 
     * @param SimpleXMLElement $xml
     * 
     * @return PMLObject
     */
    public static function createPMLfromXML(SimpleXMLElement $xml)
    {
        $obj = new self();
        
        $obj->order = [
            'number' => intval($xml->attributes()->{'number'}),
        ];

        foreach ($xml->pizza as $item) {
            $toppings = isset($item->toppings)
                ? $obj->addToppings($item)
                : null;

            $obj->pizza[] = [
                'number' => intval($item->attributes()->{'number'}),
                'size' => (string) $item->size,
                'crust' => (string) $item->crust,
                'type' => (string) $item->type,
                'toppings' => $toppings,
            ];
        }

        return $obj;
    }

    /**
     * Merge toppings to pizza element
     * @param mixed $item
     * 
     * @return array
     */
    private function addToppings($item)
    {
        $temp = [];
        foreach ($item->toppings as $topping) {
            $temp[] = [
                'area' => intval($topping->attributes()->{'area'}),
                'items' => (array) $topping->item,
            ];
            
            foreach ($topping->item as $item) {
                if (!in_array($item, $this->toppings)) {
                    $this->toppings[] = trim($item);
                }
            }
        }

        return $temp;
    }

    /**
     * Retrieve Order array
     * 
     * @return array
     */
    public function getOrderFields()
    {
        return $this->order;
    }

    /**
     * Retrieve Pizza element
     * 
     * @return array
     */
    public function getPizzaFields()
    {
        return $this->pizza;
    }

    public function getToppings()
    {
        return $this->toppings;
    }

    /**
     * Retrieve Order structure
     * 
     * @return array
     */
    public function getOrder()
    {
        $result = $this->order;
        $result['pizza'] = $this->pizza;

        return $result;
    }
}