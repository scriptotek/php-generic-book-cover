<?php

namespace Scriptotek\GenericBookCover;

class BookCover
{
    /**
     * @var bool  Whether the ImageMagick image must be re-generated
     */
    protected $dirty = true;

    /**
     * @var \Imagick  The current ImageMagick image object
     */
    protected $image;

    /**
     * @var int  Canvas width in pixels
     */
    protected $pageWidth;

    /**
     * @var int  Canvas height in pixels
     */
    protected $pageHeight;

    /**
     * @var FontMetrics
     */
    protected $fontMetrics;

    /*
    |--------------------------------------------------------------------------
    | Cover text
    |--------------------------------------------------------------------------
    */

    protected $creators = '';
    protected $title = '';
    protected $subtitle = '';
    protected $edition = '';
    protected $publisher = '';
    protected $datePublished = '';

    /*
    |--------------------------------------------------------------------------
    | Design options
    |--------------------------------------------------------------------------
    */

    /**
     * @var \ImagickPixel  Background color
     */
    protected $backgroundColor;

    /**
     * @var \ImagickPixel  Text color
     */
    protected $textColor;

    /**
     * @var string  Font name
     */
    protected $primaryFont = 'AvantGarde-Book';  // 'AvantGarde-Book'

    /**
     * @var string  Font name
     */
    protected $secondaryFont = 'Helvetica-Oblique';

    /**
     * @var string  Base cover filename
     */
    protected $baseCover;

    public function __construct()
    {
        $this->fontMetrics = new FontMetrics();
        $this->baseCover = dirname(__FILE__) . '/../assets/autocover5.png';
        $this->setTextColor('white');
        $this->setBackgroundColor('#c10001');
    }

    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    public function __set($key, $value)
    {
        $method = 'get' . ucfirst($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    public function setTextColor($color)
    {
        if (is_object($color) && $color instanceof \ImagickPixel) {
            $this->textColor = $color;
        } else {
            $this->textColor = new \ImagickPixel($color);
        }
        $this->dirty = true;

        return $this;
    }

    public function setBackgroundColor($color)
    {
        $this->backgroundColor = new \ImagickPixel($color);
        $this->dirty = true;

        return $this;
    }

    public function randomizeBackgroundColor()
    {
        $colors[0] = '#c10001';
        $colors[1] = '#fc331c';
        $colors[2] = '#ff8f00';
        $colors[3] = '#ffd221';
        $colors[4] = '#edff5b';
        $colors[5] = '#c7e000';
        $colors[6] = '#52e000';
        $colors[7] = '#00b22c';
        $colors[8] = '#1a9391';
        $colors[9] = '#00c4da';
        $colors[10] = '#4643bb';
        $colors[11] = '#610c8c';

        shuffle($colors);
        $this->setBackgroundColor($colors[0]);

        $this->dirty = true;

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->dirty = true;

        return $this;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        $this->dirty = true;

        return $this;
    }

    public function setEdition($edition)
    {
        $this->edition = $edition;
        $this->dirty = true;

        return $this;
    }

    public function setCreators($creators)
    {
        $this->creators = $creators;
        $this->dirty = true;

        return $this;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
        $this->dirty = true;

        return $this;
    }

    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;
        $this->dirty = true;

        return $this;
    }

    public function getImage($maxWidth=0)
    {
        if ($this->dirty) {
            $this->make();
        }
        $image = clone $this->image;
        if ($maxWidth > 0) {
            $image->thumbnailImage($maxWidth, 0, false);
        }

        return $image;
    }

    public function getImageBlob($maxWidth=0)
    {
        if ($this->dirty) {
            $this->make();
        }

        return $this->getImage($maxWidth)->getImageBlob();
    }

    public function save($filename, $maxWidth=0)
    {
        $fp = fopen($filename, 'w');
        fwrite($fp, $this->getImageBlob($maxWidth));
        fclose($fp);

        return $this;
    }

    protected function getDraw($gravity = null)
    {
        $draw = new \ImagickDraw();
        $draw->setFillColor($this->textColor);
        $draw->setGravity($gravity ?: \Imagick::GRAVITY_NORTHWEST);
        $draw->setFont($this->primaryFont);

        return $draw;
    }

    protected function drawTitle($top, $left, $right)
    {
        $text = mb_strtoupper($this->title);
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();
        list($fontSize, $text) = $this->fontMetrics->getFontDataForTitle($text, $this->pageWidth - $right - $left);
        $draw->setFontSize($fontSize);

        $this->image->annotateImage($draw, $left, $top, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

//        $draw = new \ImagickDraw();
//        $draw->setFillColor(new \ImagickPixel('rgba(100%, 0%, 0%, 0.5)'));
//        $draw->rectangle($left, $top, $left + $metrics['textWidth'], $top + $metrics['textHeight'] - $metrics['descender']);
//        $this->image->drawImage($draw);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawSubtitle($top, $left, $right)
    {
        $text = mb_strtoupper($this->subtitle);
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();
        list($fontSize, $text) = $this->fontMetrics->getFontDataForSubtitle($text, $this->pageWidth - $right - $left);
        $draw->setFontSize($fontSize);

        $this->image->annotateImage($draw, $left, $top, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawEdition($top, $right)
    {
        $text = mb_strtoupper($this->edition);
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw(\Imagick::GRAVITY_NORTHEAST);
        $draw->setFont($this->secondaryFont);
        $draw->setFontSize(20);

        $margin = 10;
        $this->image->annotateImage($draw, $right, $top + $margin, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawCreators($top, $left)
    {
        $text = $this->creators;
        if (empty($text)) {
            return 0;
        }

        $draw = $this->getDraw();

        list($fontSize, $text) = $this->fontMetrics->getFontDataForCreators($text);
        $draw->setFontSize($fontSize);

        $margin = 50;
        $this->image->annotateImage($draw, $left, $top + $margin, 0, $text);

        $metrics = $this->image->queryFontMetrics($draw, $text);

        return $metrics['textHeight'] - $metrics['descender'];
    }

    protected function drawPublisherDate($right, $bottom)
    {
        $text = $this->publisher . ', ' . $this->datePublished;
        if (empty($text)) {
            return 0;
        }

        $draw = new \ImagickDraw();
        $draw->setFillColor($this->textColor);
        $draw->setGravity(\Imagick::GRAVITY_SOUTHEAST);
        $draw->setFontSize(16);
        $metrics = $this->image->queryFontMetrics($draw, $text);
        $textheight = $metrics['textHeight'] - $metrics['descender'];
        //$image->annotateImage($draw, $right, $bottom, 0,$this->publisher.", ".$year." ".$color);
        $this->image->annotateImage($draw, $right, $bottom, 0, $text);

        return $textheight;
    }

    protected function make()
    {
        $left = 30;
        $right = 20;
        $top = 50;
        $bottom = 20;

        $background = new \Imagick($this->baseCover);
        list($width, $height) = array_values($background->getImageGeometry());
        $this->pageWidth = $width;
        $this->pageHeight = $height;

        $this->image = new \Imagick();
        $this->image->newImage($width, $height, $this->backgroundColor);
        $this->image->compositeImage($background, \imagick::COMPOSITE_OVER, 0, 0);

        $top += $this->drawTitle($top, $left, $right);
        $top += $this->drawSubtitle($top, $left, $right);
        $top += $this->drawEdition($top, $right);

        $this->drawCreators($top, $left);
        $this->drawPublisherDate($right, $bottom);

        $this->image->setImageFormat('png');
        $this->dirty = false;
    }
}
