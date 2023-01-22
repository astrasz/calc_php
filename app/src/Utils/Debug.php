<?php

namespace App\Utils;

trait Debug
{
    public function dump($data): void
    {
        echo '
        <div style="background-color:lightgrey; display: inline-block; padding: 0 20px; ">
        <pre>
        ';
        print_r($data);
        echo '
        </pre>
        </div>
        ';
    }
}
