<?php

namespace Jodan\PMLParser;

use RuntimeException;
use Exception;

class PMLParser
{
    private $pmlString;

    private $xml;

    private $pizzaNodeList = [
        "size",
        "crust",
        "type",
        "toppings"
    ];

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
        $start = preg_replace("/{/", "<", $this->pmlString);
        $end = preg_replace("/}/", ">", $start);

        $checker =  preg_replace("/>/", "}", preg_replace("/</", "{", $end));

        if ($checker <> $this->pmlString) {
            throw new RuntimeException("Invalid PML format!");
        }

        $xmlStr = "<?xml version='1.0' encoding='UTF-8'?>" . $end;

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

        // validate pizza
        $numbers = [];
        $previous = 0;

        foreach ($this->xml->children() as $pizza) {
            if ($pizza->getName() <> "pizza") {
                throw new RuntimeException('Unknown tag is present in PML body!');
            }

            if (!isset($pizza->attributes()['number'])) {
                throw new RuntimeException('Pizza number is not set!');
            }

            $current = $pizza->attributes()->{'number'};
            if (in_array($current, $numbers)) {
                throw new RuntimeException('Duplicate Pizza number!');
            }

            if (($current - $previous) <> 1) {
                throw new RuntimeException('Pizza number not in order!');
            }

            $this->validatePizzaFormat($pizza);

            $numbers[] = $previous = $current;
        }


        if (count($this->xml->children()) > 24) {
            throw new RuntimeException('Pizza limit has been reached!');
        }
    }

    /**
     * Validate pizza body format
     */
    private function validatePizzaFormat($pizza)
    {
        foreach ($this->pizzaNodeList as $node) {
            if (!isset($pizza->{$node}) && $node <> "toppings") {
                throw new RuntimeException(
                    "Missing $node tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }
        }

        foreach ($pizza->children() as $node) {
            if (!in_array($node->getName(), $this->pizzaNodeList)) {
                throw new RuntimeException(
                    "Unknown tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }

            if (
                $node->getName() <> "toppings"
                && count($pizza->{$node->getName()}) > 1
            ) {
                throw new RuntimeException("
                    Duplicate {$node->getName()} tag in pizza #{$pizza->attributes()->{'number'}} body!"
                );
            }

            if (
                $node->getName() == "size"
                && $pizza->type == "custom"
                && empty($pizza->toppings)
            ) {
                throw new RuntimeException("
                    Missing toppings tag in pizza #{$pizza->attributes()->{'number'}}!"
                );
            }

            if (
                $node->getName() == "size"
                && $pizza->type == "custom"
                && count($pizza->toppings) > 3
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
        $previousArea = -1;

        if (count($pizza->toppings) > 12) {
            throw new RuntimeException("
                Toppings limit reached in pizza #{$pizzaNumber}!"
            );
        }

        foreach($pizza->toppings as $toppings) {
            if (!isset($toppings->attributes()->{'area'})) {
                throw new RuntimeException("
                    Missing toppings area pizza #{$pizzaNumber}!"
                );
            }

            $currentArea = $toppings->attributes()->{'area'};

            if (
                $currentArea < 0 
                || $currentArea > 2
            ) {
                throw new RuntimeException(
                    "Unknown Toppings area in pizza #{$pizzaNumber} toppings!"
                );
            }

            
            if (($currentArea - $previousArea) <> 1) {
                throw new RuntimeException("
                    Toppings area not in order in pizza #{$pizzaNumber}!"
                );
            }
    
            if (count($toppings) == 0) {
                throw new RuntimeException("
                    Missing item in pizza #{$pizzaNumber} toppings!"
                );
            }

            $previousArea = $currentArea;
        }
    }
} 