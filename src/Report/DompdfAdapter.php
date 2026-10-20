<?php

namespace App\Report;

use App\Contract\GeradorPdfInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

final class DompdfAdapter implements GeradorPdfInterface
{
    public function gerar(string $html): string
    {
        $options = new Options();
        $options->setDefaultFont('Open Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }
}
