<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface;
use Endroid\QrCode\Color\Color;

class QrCodeService
{
    public function generateQrCode(string $text): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($text)
            ->size(100)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevelInterface::HIGH)
            ->color(new Color(0, 0, 0))
            ->backgroundColor(new Color(255, 255, 255))
            ->build();

        return $result->getDataUri();
    }
}
