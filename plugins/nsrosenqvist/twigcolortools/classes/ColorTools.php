<?php namespace NSRosenqvist\TwigColorTools\Classes;

class ColorTools {

    const COLORPERCENT = 255/100;

    public static function lighten($color, $percent)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        for ($i = 0; $i <= 2; $i++)
        {
            $color[$i] = round($color[$i] + (self::COLORPERCENT * $percent));

            if ($color[$i] > 255)
            {
                $color[$i] = 255;
            }
        }

        return self::resolveColor($color, $mode);
    }

    public static function darken($color, $percent)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        for ($i = 0; $i <= 2; $i++)
        {
            $color[$i] = round($color[$i] - (self::COLORPERCENT * $percent));

            if ($color[$i] < 0)
            {
                $color[$i] = 0;
            }
        }

        return self::resolveColor($color, $mode);
    }

    public static function red($color, $set = null)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        if ( ! is_null($set))
        {
            $newColor = [
                $set,
                $color[1],
                $color[2]
            ];

            if (self::hasAlpha($color))
            {
                $newColor[3] = $color[3];
            }

            return self::resolveColor($newColor, $mode);

        }
        else
        {
            return $color[0];
        }
    }

    public static function green($color, $set = null)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        if ( ! is_null($set))
        {
            $newColor = [
                $color[0],
                $set,
                $color[2]
            ];

            if (self::hasAlpha($color))
            {
                $newColor[3] = $color[3];
            }

            return self::resolveColor($newColor, $mode);

        }
        else
        {
            return $color[1];
        }
    }

    public static function blue($color, $set = null)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        if ( ! is_null($set))
        {
            $newColor = [
                $color[0],
                $color[1],
                $set
            ];

            if (self::hasAlpha($color))
            {
                $newColor[3] = $color[3];
            }

            return self::resolveColor($newColor, $mode);
        }
        else
        {
            return $color[2];
        }
    }

    public static function alpha($color, $set = null)
    {
        $mode = "";
        $color = self::normalizeColor($color, $mode);

        if ( ! is_null($set))
        {
            $color[3] = ($set/100);
            return self::resolveColor($color, 'rgb');
        }
        else
        {
            return (self::hasAlpha($color)) ? $color[3] : (float) 1;
        }
    }

    public static function mix($colors)
    {
        if ( ! is_array($colors))
            $colors = func_get_args();
        if (empty($colors))
            return "";
        if (count($colors) == 1)
            return $colors[0];

        $hexColors = [];
        $amountColors = count($colors);

        foreach ($colors as $key => $val)
        {
            $colors[$key] = self::normalizeColor($val);
        }

        $totalRed = 0;
        $totalGreen = 0;
        $totalBlue = 0;

        foreach ($colors as $color)
        {
            $totalRed += $color[0];
            $totalGreen += $color[1];
            $totalBlue += $color[2];
        }

        $color = [
            round($totalRed / $amountColors),
            round($totalGreen / $amountColors),
            round($totalBlue / $amountColors),
        ];

        return self::resolveColor($color);
    }

    protected static function normalizeColor($color, &$mode = null)
    {
        $color = strtolower($color);

        if (strpos($color, 'rgb') !== false)
        {
            $mode = 'rgb';
            $color = trim($color, 'rgba()');
            $color = str_replace(' ', '', $color);
            $color = explode(',', $color);
            $count = count($color);

            for ($i = 0; $i <= $count; $i++)
            {
                $color[$i] = ($i == 3) ? (float) $color[$i] : (int) $color[$i];
            }
        }
        else
        {
            $mode = 'hex';
            $color = str_replace('#', '', $color);

            if (strlen($color) == 3)
            {
                $color = str_repeat(substr($color,0,1), 2) . str_repeat(substr($color,1,1), 2) . str_repeat(substr($color,2,1), 2);
            }

            $color = [
                hexdec(substr($color, 0, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 4, 2))
            ];
        }

        return $color;
    }

    protected static function resolveColor($color, $mode = 'hex')
    {
        switch ($mode)
        {
            case "hex":
                $red = sprintf('%02x', ($color[0]));
                $green = sprintf('%02x', ($color[1]));
                $blue = sprintf('%02x', ($color[2]));
                $color = '#'.$red.$green.$blue;
                break;
            case "rgb":
                $colorStr = (self::hasAlpha($color)) ? 'rgba(' : 'rgb(';
                $colorStr .= '('.$color[0].','.$color[1].','.$color[2];
                $colorStr .= (self::hasAlpha($color)) ? ','.number_format($color[3], 2, '.', ',').')' : ')';
                $color = $colorStr;
                break;
            default:
                $color = "";
        }

        return $color;
    }

    protected static function hasAlpha($color)
    {
        return (count($color) > 3) ? true : false;
    }
}
