<?php

namespace App\UseCases;


interface HandlerInterface
{
    public function handle(CommandInterface $command);
}