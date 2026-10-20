<?php

namespace App\Contract;

interface GeradorPdfInterface
{
    public function gerar(string $html): string;
}
