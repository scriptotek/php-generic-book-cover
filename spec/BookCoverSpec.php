<?php

namespace spec\Scriptotek\GenericBookCover;

use PhpSpec\ObjectBehavior;

class BookCoverSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Scriptotek\GenericBookCover\BookCover');
    }

    public function it_supports_setting_a_text_color()
    {
        $this->setTextColor('#345');
        $this->textColor->shouldHaveType('ImagickPixel');
        $this->textColor->isSimilar(new \ImagickPixel('#345'), 0.01)->shouldBe(true);
    }

    public function it_supports_setting_a_background_color()
    {
        $this->setBackgroundColor('#201');
        $this->backgroundColor->shouldHaveType('ImagickPixel');
        $this->backgroundColor->isSimilar(new \ImagickPixel('#201'), 0.01)->shouldBe(true);
    }

    public function it_supports_setting_a_title()
    {
        $this->setTitle('Dummy text');
        $this->title->shouldBe('Dummy text');
    }

    public function it_supports_setting_a_subtitle()
    {
        $this->setSubtitle('Dummy text');
        $this->subtitle->shouldBe('Dummy text');
    }

    public function it_supports_setting_creators()
    {
        $this->setCreators('Dummy text');
        $this->creators->shouldBe('Dummy text');
    }

    public function it_supports_setting_an_edition()
    {
        $this->setEdition('Dummy text');
        $this->edition->shouldBe('Dummy text');
    }

    public function it_supports_setting_a_publisher()
    {
        $this->setPublisher('Dummy text');
        $this->publisher->shouldBe('Dummy text');
    }

    public function it_supports_setting_a_publication_date()
    {
        $this->setDatePublished('Dummy text');
        $this->datePublished->shouldBe('Dummy text');
    }

    public function it_produces_a_500_px_wide_image_by_default()
    {
        $image = $this->getImage();
        $image->shouldHaveType('Imagick');
        $image->getImageWidth()->shouldBe(500);
        $image->getImageHeight()->shouldBe(739);
    }

    public function it_can_produce_an_image_of_any_width()
    {
        $image = $this->getImage(300);
        $image->shouldHaveType('Imagick');
        $image->getImageWidth()->shouldBe(300);
        $image->getImageHeight()->shouldBe(443);
    }

    public function it_produces_PNG_data()
    {
        $image = $this->getImageBlob();
        $image->shouldBeString();
        $image->shouldMatch('/PNG/i');
    }
}
