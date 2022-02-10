<?php
declare(strict_types = 1);

namespace App\Libraries\BaconQrCode\Renderer\Module;

use App\Libraries\BaconQrCode\Encoder\ByteMatrix;
use App\Libraries\BaconQrCode\Exception\InvalidArgumentException;
use App\Libraries\BaconQrCode\Renderer\Module\EdgeIterator\EdgeIterator;
use App\Libraries\BaconQrCode\Renderer\Path\Path;

/**
 * Renders individual modules as dots.
 */
final class GeneralShapeModule implements ModuleInterface
{
    public const LARGE = 1;
    public const MEDIUM = .8;
    public const SMALL = .6;

    private static $instance;

    /**
     * @var float
     */
    private $size;

    private $intensity;

    private static $style;


    public function __construct($size)
    {
        $this->size = $size;
    }

    public static function instance(float $size,string $style) : self
    {
        if ($size <= 0 || $size > 1) {
            throw new InvalidArgumentException('Size must between 0 (exclusive) and 1 (inclusive)');
        }

        self::$style = $style;

        return self::$instance ?: self::$instance = new self($size);
    }

    public function createPath(ByteMatrix $matrix) : Path
    {
        $styles  = [
            'square',
            'dot',
            'round',
            'shape1',
            'shape2',
            'shape3',
            'shape4',
            'shape5',
            'shape6',
            'shape7'
        ];

        if (!self::$style) {
            self::$style = 'square';
        }
        $ShapePosition = array_keys($styles, self::$style);

        switch ($ShapePosition[0]) {
            case 0:
                $path = new Path();

                foreach (new EdgeIterator($matrix) as $edge) {
                    $points = $edge->getSimplifiedPoints();
                    $length = count($points);
                    $path = $path->move($points[0][0], $points[0][1]);

                    for ($i = 1; $i < $length; ++$i) {
                        $path = $path->line($points[$i][0], $points[$i][1]);
                    }

                    $path = $path->close();
                }

                return $path;
                break;
            case 1:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {
                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
            case 2:
                $path = new Path();

                foreach (new EdgeIterator($matrix) as $edge) {
                    $points = $edge->getSimplifiedPoints();
                    $length = count($points);

                    $currentPoint = $points[0];
                    $nextPoint = $points[1];
                    $horizontal = ($currentPoint[1] === $nextPoint[1]);

                    if ($horizontal) {
                        $right = $nextPoint[0] > $currentPoint[0];
                        $path = $path->move(
                            $currentPoint[0] + ($right ? $this->intensity : -$this->intensity),
                            $currentPoint[1]
                        );
                    } else {
                        $up = $nextPoint[0] < $currentPoint[0];
                        $path = $path->move(
                            $currentPoint[0],
                            $currentPoint[1] + ($up ? -$this->intensity : $this->intensity)
                        );
                    }

                    for ($i = 1; $i <= $length; ++$i) {
                        if ($i === $length) {
                            $previousPoint = $points[$length - 1];
                            $currentPoint = $points[0];
                            $nextPoint = $points[1];
                        } else {
                            $previousPoint = $points[(0 === $i ? $length : $i) - 1];
                            $currentPoint = $points[$i];
                            $nextPoint = $points[($length - 1 === $i ? -1 : $i) + 1];
                        }

                        $horizontal = ($previousPoint[1] === $currentPoint[1]);

                        if ($horizontal) {
                            $right = $previousPoint[0] < $currentPoint[0];
                            $up = $nextPoint[1] < $currentPoint[1];
                            $sweep = ($up xor $right);

                            if ($this->intensity < 0.5
                                || ($right && $previousPoint[0] !== $currentPoint[0] - 1)
                                || (! $right && $previousPoint[0] - 1 !== $currentPoint[0])
                            ) {
                                $path = $path->line(
                                    $currentPoint[0] + ($right ? -$this->intensity : $this->intensity),
                                    $currentPoint[1]
                                );
                            }

                            $path = $path->ellipticArc(
                                $this->intensity,
                                $this->intensity,
                                0,
                                false,
                                $sweep,
                                $currentPoint[0],
                                $currentPoint[1] + ($up ? -$this->intensity : $this->intensity)
                            );
                        } else {
                            $up = $previousPoint[1] > $currentPoint[1];
                            $right = $nextPoint[0] > $currentPoint[0];
                            $sweep = ! ($up xor $right);

                            if ($this->intensity < 0.5
                                || ($up && $previousPoint[1] !== $currentPoint[1] + 1)
                                || (! $up && $previousPoint[0] + 1 !== $currentPoint[0])
                            ) {
                                $path = $path->line(
                                    $currentPoint[0],
                                    $currentPoint[1] + ($up ? $this->intensity : -$this->intensity)
                                );
                            }

                            $path = $path->ellipticArc(
                                $this->intensity,
                                $this->intensity,
                                0,
                                false,
                                $sweep,
                                $currentPoint[0] + ($right ? $this->intensity : -$this->intensity),
                                $currentPoint[1]
                            );
                        }
                    }

                    $path = $path->close();
                }

                return $path;
                break;
            case 3:
                $this->intensity = $this->size / 2;
                $path = new Path();

                foreach (new EdgeIterator($matrix) as $edge) {
                    $points = $edge->getSimplifiedPoints();
                    $currentPoint = $points[0];
                    $nextPoint = $points[1];
                    $length = count($points);
                    $path = $path->move($points[0][0], $points[0][1]);
                    $horizontal = ($currentPoint[1] === $nextPoint[1]);

                    if ($horizontal) {
                        $right = $nextPoint[0] > $currentPoint[0];
                        $path = $path->move(
                            $currentPoint[0] - ($right ? $this->intensity : $this->intensity),
                            $currentPoint[1]
                        );
                    } else {
                        $up = $nextPoint[0] < $currentPoint[0];
                        $path = $path->move(
                            $currentPoint[0],
                            $currentPoint[1] - ($up ? $this->intensity : $this->intensity)
                        );
                    }

                    for ($i = 1; $i <= $length; ++$i) {
                        if ($i === $length) {
                            $previousPoint = $points[$length - 1];
                            $currentPoint = $points[0];
                            $nextPoint = $points[1];
                        } else {
                            $previousPoint = $points[(0 === $i ? $length : $i) - 1];
                            $currentPoint = $points[$i];
                            $nextPoint = $points[($length - 1 === $i ? -1 : $i) + 1];
                        }

                        $horizontal = ($previousPoint[1] === $currentPoint[1]);

                        if ($horizontal) {
                            $right = $previousPoint[0] < $currentPoint[0];
                            $up = $nextPoint[1] < $currentPoint[1];
                            $sweep = ($up xor $right);

                            if ($this->intensity < 0.5
                                || ($right && $previousPoint[0] !== $currentPoint[0] - 1)
                                || (! $right && $previousPoint[0] - 1 !== $currentPoint[0])
                            ) {
                                $path = $path->line($currentPoint[0] ,
                                    $currentPoint[1] - ($up ? -$this->intensity : $this->intensity));
                            }
                            /*Horizontal boxes corner change*/
                            $path = $path->ellipticArc(
                                $this->intensity,
                                $this->intensity,
                                0,
                                false,
                                $sweep,
                                $currentPoint[0] ,
                                $currentPoint[1] + ($up ? -$this->intensity : $this->intensity)
                            );
                        } else {
                            $up = $previousPoint[1] > $currentPoint[1];
                            $right = $nextPoint[0] > $currentPoint[0];
                            $sweep = ! ($up xor $right);

                            if ($this->intensity < 0.5
                                || ($up && $previousPoint[1] !== $currentPoint[1] + 1)
                                || (! $up && $previousPoint[0] + 1 !== $currentPoint[0])
                            ) {
                                $path = $path->line(
                                    $currentPoint[0],
                                    $currentPoint[1] - ($up ? -$this->intensity : $this->intensity)
                                );
                            }
                            /*Corner side rounded*/
                            $path = $path->ellipticArc(
                                $this->intensity,
                                $this->intensity,
                                0,
                                false,
                                $sweep,
                                $currentPoint[0] ,
                                $currentPoint[1] + ($up ? -$this->intensity : $this->intensity)
                            );
                        }
                    }
                    $path = $path->close();

                }

                return $path;
                break;
            case 4:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {
                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
            case 5:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {
                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, false, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, false, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
            case 6:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {
                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, true, false, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, true, false, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;

                break;
            case 7:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {
                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($this->size, $this->size, 0, false, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($this->size, $this->size, 0, false, true, $pathX, $pathY + $halfSize)
                            ->line($pathX - $this->size, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
            case 8:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {

                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
            case 9:
                $width = $matrix->getWidth();
                $height = $matrix->getHeight();
                $path = new Path();
                $halfSize = $this->size / 2;
                $margin = (1 - $this->size) / 2;

                for ($y = 0; $y < $height; ++$y) {

                    for ($x = 0; $x < $width; ++$x) {
                        if (! $matrix->get($x, $y)) {
                            continue;
                        }

                        $pathX = $x + $margin;
                        $pathY = $y + $margin;

                        $path = $path
                            ->move($pathX + $this->size, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX + $halfSize, $pathY + $this->size)
                            ->ellipticArc($halfSize, $halfSize, 0, true, true, $pathX, $pathY + $halfSize)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $halfSize, $pathY)
                            ->ellipticArc($halfSize, $halfSize, 0, false, true, $pathX + $this->size, $pathY + $halfSize)
                            ->close()
                        ;
                    }
                }

                return $path;
                break;
        }

    }
}
