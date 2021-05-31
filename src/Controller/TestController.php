<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;


class TestController
{

    public function test()
    {
        return new Response("Ceci est une 2ème page de test");
    }
}
