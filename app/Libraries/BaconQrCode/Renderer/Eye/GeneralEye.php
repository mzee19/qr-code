<?php
declare(strict_types=1);

namespace App\Libraries\BaconQrCode\Renderer\Eye;

use App\Libraries\BaconQrCode\Renderer\Path\Path;

/**
 * Renders the eyes in their default square shape.
 */
final class GeneralEye implements EyeInterface
{
    /**
     * @var self|null
     */
    private static $instance;

    public static $eyeStyle;

    public static $eyeFrame;

    private function __construct()
    {

    }

    public static function instance($eyeStyle, $eyeFrame): self
    {
        self::$eyeStyle = $eyeStyle;
        self::$eyeFrame = $eyeFrame;
        return self::$instance ?: self::$instance = new self();
    }

    public function getExternalPath(): Path
    {
        $frames = [
            'square',
            'frame1',
            'frame2',
            'frame3',
            'frame4',
            'frame5',
        ];
        if (!self::$eyeFrame) {
            self::$eyeFrame = 'square';
        }

        $framePosition = array_keys($frames, self::$eyeFrame);
        switch ($framePosition[0]) {
            case 0:
                return (new Path())
                    ->move(-3.5, -3.5)
                    ->line(3.5, -3.5)
                    ->line(3.5, 3.5)
                    ->line(-3.5, 3.5)
                    ->close()
                    ->move(-2.5, -2.5)
                    ->line(-2.5, 2.5)
                    ->line(2.5, 2.5)
                    ->line(2.5, -2.5)
                    ->close()
                    ;
                break;
            case 1:
                return (new Path())
                    ->move(-3, -3.5)
                    ->line(3.5, -3.5)
                    ->line(3.5, 3.5)
                    ->line(-3.5, 3.5)
                    ->close()
                    ->move(-2, -2.5)
                    ->line(-2.5, 2.5)
                    ->line(2.5, 2.5)
                    ->line(2.5, -2.5)
                    ->close();
                break;
            case 2 :
                return (new Path())
                    ->move(-3, -3.5)
                    ->line(3.5, -3.5)
                    ->line(3, 3.5)
                    ->line(-3.5, 3.5)
                    ->close()
                    ->move(-2, -2.5)
                    ->line(-2.5, 2.5)
                    ->line(2, 2.5)
                    ->line(2.5, -2.5)
                    ->close();
                break;
            case 3 :
                return (new Path())
                    ->move(-3.5, -3.5)
                    ->line(3.5, -3.5)
                    ->line(3.5, -2.5)
                    ->curve(2.5, 2.5, 3.5, 1.5, 3.5, 3.5)
                    ->line(-3.5, 3.5)
                    ->close()
                    ->move(-2.5, -2.5)
                    ->line(-2.5, 2.5)
                    ->line(2, 2.5)
                    ->curve(3, 2.5, 1.5, 3, 2.7, -2.5)
                    ->line(2.5, -2.5)
                    ->close();
                break;
            case 4 :
                return (new Path())
                    ->move(-3.5, -3.5)
                    ->ellipticArc(2, .1, 0, false, false, 3.5, -3.5)
                    ->ellipticArc(.1, .7, 0, false, false, 3.5, 3.5)
                    ->ellipticArc(2, .1, 0, false, false, -3.5, 3.5)
                    ->ellipticArc(.1, .7, 0, false, false, -3.5, -3.5)
                    ->close()
                    ->move(-2.5, -2.5)
                    ->ellipticArc(2, .1, 0, false, false, 2.5, -2.5)
                    ->ellipticArc(.1, .9, 0, false, false, 2.5, 2.5)
                    ->ellipticArc(2, .1, 0, false, false, -2.5, 2.5)
                    ->ellipticArc(.1, .9, 0, false, false, -2.5, -2.5)
                    ->close();
                break;
            case 5:
                return (new Path())
                    ->move(-3.5, -3.5)
                    ->ellipticArc(2, .1, 0, false, true, 3.5, -3.5)
                    ->ellipticArc(.1, .7, 0, false, true, 3.5, 3.5)
                    ->ellipticArc(2, .1, 0, false, true, -3.5, 3.5)
                    ->ellipticArc(.1, .7, 0, false, true, -3.5, -3.5)
                    ->close()
                    ->move(-2.5, -2.5)
                    ->ellipticArc(2, .1, 0, false, true, 2.5, -2.5)
                    ->ellipticArc(.1, .9, 0, false, true, 2.5, 2.5)
                    ->ellipticArc(2, .1, 0, false, true, -2.5, 2.5)
                    ->ellipticArc(.1, .9, 0, false, true, -2.5, -2.5)
                    ->close();
                break;

            default:
                return (new Path())
                    ->move(-3.5, -3.5)
                    ->line(3.5, -3.5)
                    ->line(3.5, 3.5)
                    ->line(-3.5, 3.5)
                    ->close()
                    ->move(-2.5, -2.5)
                    ->line(-2.5, 2.5)
                    ->line(2.5, 2.5)
                    ->line(2.5, -2.5)
                    ->close();

        }

    }

    public function getInternalPath(): Path
    {
        $eyeStyles = [
            'square',
            'circle',
            'eye1',
            'eye2',
            'eye3',
            'eye4',
            'eye5',
            'eye6',
            'eye7',
            'eye8',
            'eye9',
            'eye10',
            'eye11',
            'eye12',
            'eye13',
            'eye14',
            'eye15',
            'eye16',
            'eye17',
            'eye18',
            'eye19',
            'eye20',
            'eye21',
            'eye22',
            'eye23',
            'eye24',
            'eye25',
            'eye26',
            'eye27',
            'eye28',
            'eye29',
            'eye30',
        ];
        if (!self::$eyeStyle) {
            self::$eyeStyle = 'square';
        }
        $stylePosition = array_keys($eyeStyles, self::$eyeStyle);
        switch ($stylePosition[0]) {
            case 0:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 1 :
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 1.5, 0.)
                    ->close();
                break;
            case 2 :
                return (new Path())
                    ->move(-0.5, -1.5)
                    ->line(2.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
            case 3 :
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., -1.5)
                    ->close();
                break;
            case 4:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., true, true, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., true, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., true, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., true, true, 1.5, 0.)
                    ->close();
                break;

            case 5:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., true, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 1.5, 0.)
                    ->close();
                break;
            case 6:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, false, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 1.5, 0.)
                    ->close();
                break;
            case 7:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(4.5, 4.5, 0., false, false, 0., 1.5)
                    ->ellipticArc(4.5, 4.5, 0., false, false, -1.5, 0.)
                    ->ellipticArc(4.5, 4.5, 0., false, false, 0., -1.5)
                    ->ellipticArc(4.5, 4.5, 0., false, false, 1.5, 0.)
                    ->close();
                break;
            case 8:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, false, 0., 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, false, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 1.5, 0.)
                    ->close();
                break;
            case 9:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 10:
                return (new Path())
                    ->move(1.5, 0)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., 1.5)
                    ->line(-2, 1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, -1.5, 0.)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 0., -1.5)
                    ->ellipticArc(1.5, 1.5, 0., false, true, 1.5, 0.)
                    ->close();
                break;
            case 11:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.2, -1.2)
                    ->line(1.5, 1.5)
                    ->line(-1.2, 1.2)
                    ->close();
                break;
            case 12:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-2, 2)
                    ->close();
                break;
            case 13:
                return (new Path())
                    ->move(-2, -2)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-2, 2)
                    ->close();
                break;
            case 14:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(3.5, 0.9, 0., false, true, 0., 0)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 15:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(0, 0, 0, false, false, 0, -1)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 15:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(0, 0, 0, false, false, 0, -1)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->ellipticArc(0, 0, 0, false, false, 0, 2)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 16:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(-0.7, 0.4, 0, false, true, 1.5, -0.9)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 17:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->ellipticArc(-0.7, 0.4, 0, false, true, 0.2, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 18:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->ellipticArc(-.7, 1, 0, false, true, -1, -1.5)
                    ->close();
                break;
            case 19:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(-0.6, 0.6, 0, false, true, -0.1, -1.5)
                    ->line(1.5, -1.5)
                    ->ellipticArc(-0.4, 0.6, 0, false, true, 1.5, 0.1)
                    ->line(1.5, 1.5)
                    ->ellipticArc(-0.7, 0.4, 0, false, true, 0.2, 1.5)
                    ->line(-1.5, 1.5)
                    ->ellipticArc(0.6, -0.8, 0, false, true, -1.5, -0.1)
                    ->close();
                break;
            case 20:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(-0.6, 0.2, 0, false, false, 1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->ellipticArc(-0.7, 0.2, 0, false, false, -1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->close();
                break;
            case 21:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->ellipticArc(-0.6, 0.2, 0, false, false, 1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->ellipticArc(-0.6, 1.7, 0, false, false, 1.5, 1.5)
                    ->line(1.5, 1.5)
                    ->ellipticArc(-0.7, 0.2, 0, false, false, -1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->ellipticArc(-0.7, 1.7, 0, false, false, -1.5, -1.5)
                    ->close();
                break;
            case 22:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->ellipticArc(-0.6, 1.7, 0, false, false, 1.5, 1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->ellipticArc(-0.7, 1.7, 0, false, false, -1.5, -1.5)
                    ->close();
                break;
            case 23:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->ellipticArc(-0.6, 0.2, 0, false, false, 1.5, -1.5)
                    ->ellipticArc(-0.6, 1.7, 0, false, true, 1.5, 1.5)
                    ->line(1.5, 1.5)
                    ->ellipticArc(-0.7, 0.2, 0, false, false, -1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->ellipticArc(-0.7, 1.7, 0, false, true, -1.5, -1.5)
                    ->close();
                break;
            case 24:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->curve(-1.5, -2.1, -1.2, -1.5, 1.5, -1.5)
                    ->close();
                break;
            case 25:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->line(1.5, -1.5)
                    ->curve(1.5, -2.1, 2, 1.5, 0.8, 1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->curve(-1.5, -2.1, -1.2, -1.5, 1.5, -1.5)
                    ->close();
                break;
            case 26:
                return (new Path())
                    ->move(-1.5, -1.5)
                    ->curve(3.5, -1.5, 0, -1.5, 1.5, 1.5)
                    ->line(1.5, -1.5)
                    ->line(1.5, 1.5)
                    ->line(-1.5, 1.5)
                    ->curve(-1.5, -2.1, -1.2, -1.5, 1.5, -1.5)
                    ->close();
                break;
            case 27:
                return (new Path())
                    ->move(-1, -1.5)
                    ->curve(2.5, -2.2, 1.2, -2.4, 1.5, 1)
                    ->line(1.5, -1.5)
                    ->curve(1.5, -2.1, 2, 1.5, 0.8, 1.5)
                    ->line(1, 1.5)
                    ->curve(-2.5, 2.2, -1.2, 2.4, -1.5, 0)
                    ->line(-1.5, 1.5)
                    ->curve(-1.5, -2.1, -1.2, -1.5, 1.5, -1.5)
                    ->close();
                break;
            default:
                return (new Path())
                    ->move(-1., -1.5)
                    ->curve(2.5, -2, 1, 0.5, 1.5, -0.5)
                    ->curve(1.3, -.2, 1.9, .5, .5, 1.5)
                    ->curve(-1.5, 1.5, -1.2, .2 , -1.2, .1)
                    ->curve(-1.3, -2.5, 0, -1 , 1., -.009)
                    ->close();

        }

    }
}
