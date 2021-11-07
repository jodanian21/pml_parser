<?php

namespace Jodan\PMLParser;

use App\Constants\Size;
use RuntimeException;
use Exception;

class PMLParser
{
    const MAX_PIZZA_COUNT = 24;

    const MAX_TOPPINGS_COUNT = 12;

    const MAX_TOPPINGS_TAG_COUNT = 3;

    private $pizzaNodeList = [
        "size",
        "crust",
        "type",
        "toppings"
    ];

    private $pmlString;

    private $xml;

    /**
     * Main parser method
     */
    public function parse(?string $str)
    {
        $this->pmlString = $str;

        $this->convertToXML();

        $this->validatePMLFormat();

        return PMLObject::createPMLfromXML($this->xml);
    }

    /**
     * Convert to XML format for easy traversal
     */
    public function convertToXML() {
        $xml = preg_replace("/{/", "<", $this->pmlString);
        $xml = preg_replace("/}/", ">", $xml);

        $checker =  preg_replace("/>/", "}", preg_replace("/</", "{", $xml));
        // check for angle tags if added
        if ($checker <> $this->pmlString) {
            throw new RuntimeException("Invalid PML format!");
        }
        // append xml parent tag
        $xmlStr = "<?xml version='1.0' encoding='UTF-8'?>" . $xml;
        // enable xml parsing errors
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlStr);

        if ($xml === false) {
            $msg = "Failed loading XML: ";
            foreach(libxml_get_errors() as $error) {
                $msg .= $error->message . ';';
            }

            throw new Exception($msg);
        }

        $this->xml = $xml;
    }

    /**
     * Validate order body format
     */
    private function validatePMLFormat()
    {
        // validate order number
        if (!isset($this->xml->attributes()['number'])) {
            throw new RuntimeException('Order number is not set!');
        }

        if (
            $this->xml->attributes()['number'] <>
            (string) intval($this->xml->attributes()['number'])
        ) {
            throw new RuntimeException('Order Number invalid type!');
        }

        // validate pizza tags
        $numbers = [];
        $previous = 0;

        if (!isset($this->xml->pizza)) {
            throw new RuntimeException('Pizza not set!');
        }

        foreach ($this->xml->children() as $pizza) {
            if ($pizza->getName() <> "pizza") {
                throw new RuntimeException('Unknown tag is present in Order body!');
            }

            if (!isset($pizza->attributes()['number'])) {
                throw new RuntimeException(' number is not set!');
            }

            if (
                $pizza->attributes()['number'] <> 
                (string) intval($pizza->attributes()['number'])
            ) {
                throw new RuntimeException('Pizza Number invalid type!');
            }

            $current = $pizza->attributes()->{'number'};
            if (in_array($current, $numbers)) {
                throw new RuntimeException('Duplicate Pizza number!');
            }

            if (($current - $previous) <> 1) {
                throw new RuntimeException('Pizza number not in order!');
            }

            // validate pizza children tags
            $this->validatePizzaFormat($pizza);

            $numbers[] = $previous = $current;
        }

        if (count($this->xml->children()) > self::MAX_PIZZA_COUNT) {
            throw new RuntimeException('Pizza limit has been reached!');
        }
    }

    /**
     * Validate pizza body format
     */
    private function validatePizzaFormat($pizza)
    {
        // check allowed tags in pizza body
        foreach ($this->pizzaNodeList as $node) {
            if (!isset($pizza->{$node}) && $node <> "toppings") {
                throw new RuntimeException(
                    "Missing $node tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }

            if (empty($pizza->{$node}) && $node <> "toppings") {
                throw new RuntimeException(
                    "No value for $node tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }
        }

        // check children nodes
        foreach ($pizza->children() as $node) {
            if (!in_array($node->getName(), $this->pizzaNodeList)) {
                throw new RuntimeException(
                    "Unknown tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }

            // check for duplicate tags
            if (
                $node->getName() <> "toppings"
                && count($pizza->{$node->getName()}) > 1
            ) {
                throw new RuntimeException("
                    Duplicate {$node->getName()} tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }

            // check of custom pizza has no toppings
            if (
                $pizza->type == "custom"
                && !isset($pizza->toppings)
            ) {
                throw new RuntimeException("
                    Missing toppings tag in pizza #{$pizza->attributes()->{'number'}}!"
                );
            }

            // check if reach topping limit
            if (
                $pizza->type == "custom"
                && count($pizza->toppings) > self::MAX_TOPPINGS_TAG_COUNT
            ) {
                throw new RuntimeException("
                    Toppings limit reached in pizza #{$pizza->attributes()->{'number'}}!"
                );
            }
        }

        // validate toppings
        if ($pizza->type == "custom") {
            $this->validateToppings($pizza, $pizza->attributes()->{'number'});
        }
    }

    /**
     * Validate pizza body format
     */
    private function validateToppings($pizza, $pizzaNumber)
    {
        // check if topping tag count limit is reached
        if (count($pizza->toppings) > self::MAX_TOPPINGS_COUNT) {
            throw new RuntimeException("
            Toppings limit reached in pizza #{$pizzaNumber}!"
            );
        }
        
        // check for area property of toppings
        $previousArea = [];
        foreach($pizza->toppings as $toppings) {
            if (!isset($toppings->attributes()->{'area'})) {
                throw new RuntimeException("
                    Toppings area not set in pizza #{$pizzaNumber}!"
                );
            }

            // check for correct value of toppings
            $currentArea = $toppings->attributes()->{'area'};
            if (
                $currentArea < 0
                || $currentArea > 2
                ) {
                    throw new RuntimeException(
                    "Unknown toppings area in pizza #{$pizzaNumber} toppings!"
                );
            }
            
            // check if order of toppings area is correct
            if (in_array(intval($currentArea), $previousArea)) {
                throw new RuntimeException("
                    Duplicate toppings area in pizza #{$pizzaNumber}!"
                );
            }
    
            // check if no items tag
            if (count($toppings) == 0) {
                throw new RuntimeException("
                    Missing item in pizza #{$pizzaNumber} toppings!"
                );
            }

            $previousArea[] = intval($currentArea);
        }
    }
} 