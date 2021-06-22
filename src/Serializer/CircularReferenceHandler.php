<?php


namespace App\Serializer;


class CircularReferenceHandler
{
    public function __invoke($object){
        //TODO generate and return url to resource?
        return $object->getId();
    }
}