<?php
declare(strict_types = 1);

namespace App\Libraries\BaconQrCode\Renderer;

use App\Libraries\BaconQrCode\Encoder\QrCode;

interface RendererInterface
{
    public function render(QrCode $qrCode) : string;
}
