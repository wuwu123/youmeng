<?php


namespace Rongun\Push;

interface PayLoad
{
    public static function make();

    public function getData(): array;
}
