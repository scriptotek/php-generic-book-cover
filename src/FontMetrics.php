<?php

namespace Scriptotek\GenericBookCover;

class FontMetrics
{
    protected $baseFontFactor = 205;
    protected $titleFontSize = 0;

    /**
     * Get the max number of characters that can be fitted in the width
     * for a given font size before wrapping is needed.
     *
     * @param $x
     * @return int
     */
    public function maxWidthFromFontSize($x)
    {
        if ($x < 22) return 37;
        if ($x < 24) return 36;
        if ($x < 25) return 35;
        if ($x < 28) return 32;
        if ($x < 29) return 30;
        if ($x < 31) return 29;
        if ($x < 34) return 26;
        if ($x < 35) return 25;
        if ($x < 37) return 24;
        if ($x < 39) return 23;
        if ($x < 44) return 19;
        if ($x < 45) return 18;
        if ($x < 51) return 16;
        if ($x < 53) return 15;
        if ($x < 54) return 14;
        if ($x < 62) return 13;
        if ($x < 63) return 12;
        if ($x < 77) return 11;
        if ($x < 92) return 9;
        if ($x < 100) return 8;
        return 7;
    }

    /**
     * Get the maximum font size that can be used if a given number of characters
     * need to be fitted in the width.
     *
     * @param $x
     * @return int
     */
    public function maxFontSizeFromWidth($x)
    {
        if ($x < 7) return 95;
        if ($x < 8) return 84;
        if ($x < 9) return 88;
        if ($x < 10) return 82;
        if ($x < 11) return 76;
        if ($x < 12) return 68;
        if ($x < 13) return 62;
        if ($x < 14) return 58;
        if ($x < 15) return 56;
        if ($x < 16) return 53;
        if ($x < 17) return 50;
        if ($x < 18) return 45;
        if ($x < 19) return 43;
        if ($x < 20) return 40;
        if ($x < 21) return 38;
        if ($x < 22) return 36;
        if ($x < 23) return 34;
        if ($x < 24) return 32;
        if ($x < 25) return 30;
        if ($x < 26) return 29;
        if ($x < 27) return 28;
        if ($x < 28) return 27;
        if ($x < 29) return 27;
        if ($x < 30) return 26;
        if ($x < 31) return 25;
        if ($x < 32) return 24;
        if ($x < 33) return 23;
        return 20;
    }

    protected function getLongest($tokens)
    {
        $longestToken = '';
        foreach ($tokens as $token) {
            $token = trim($token);
            if (strlen($token) > strlen($longestToken)) {
                $longestToken = $token;
            }
        }

        return $longestToken;
    }

    protected function optimizeFontSize($str, $fontSize, $pageWidth)
    {
        $im = new \Imagick();
        $draw = new \ImagickDraw();
        $draw->setFont('AvantGarde-Book');
        $fontSize += 1;
        do {
            $fontSize -= 1;
            $draw->setFontSize($fontSize);
            $metrics = $im->queryFontMetrics($draw, $str);
            $textWidth = $metrics['textWidth'];
        } while ($textWidth > $pageWidth);

        return $fontSize;
    }

    public function getFontDataForTitle($str, $pageWidth)
    {
        $str = trim($str);
        $longestToken = $this->getLongest(explode(' ', $str));

        $fontSize = round($this->baseFontFactor / pow(strlen($str), 0.465), 0);
        $width = $this->maxWidthFromFontSize($fontSize);

        if (strlen($longestToken) > $width) {
            $width = strlen($longestToken);
            $fontSize = min($fontSize, $this->maxFontSizeFromWidth($width));
        }

        $str = trim(wordwrap($str, $width));

        $fontSize = $this->optimizeFontSize($str, $fontSize, $pageWidth);
        $this->titleFontSize = $fontSize;

        return [$fontSize, $str];
    }

    public function getFontDataForSubtitle($str, $pageWidth)
    {
        $str = trim($str);
        $longestToken = $this->getLongest(explode(' ', $str));

        $fontSize = round(0.68 * $this->baseFontFactor / pow(strlen($str), 0.465), 0);

        // Size of subtitle should never be more than 0.9 times the title size
        $fontSize = min($fontSize, $this->titleFontSize * 0.9);

        $width = $this->maxWidthFromFontSize($fontSize);
        if (strlen($longestToken) > $width) {
            $width = strlen($longestToken);
            $fontSize = min($fontSize, $this->maxFontSizeFromWidth($width));
        }

        $str = trim(wordwrap($str, $width));
        $fontSize = $this->optimizeFontSize($str, $fontSize, $pageWidth);

        return [$fontSize, $str];
    }

    public function getFontDataForCreators($str)
    {
        $str = trim($str);
        $items = preg_split('/,\s*/', $str);
        $longestToken = $this->getLongest($items);

        $fontSize = round(0.45 * $this->baseFontFactor / pow(strlen($str), 0.33), 0);
        $fontSize = min($fontSize, $this->maxFontSizeFromWidth(strlen($longestToken)));

        $str = trim(implode("\n", $items));

        return [$fontSize, $str];
    }
}
