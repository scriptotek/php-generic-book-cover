# php-generic-book-cover

This is a small package for generating generic book covers that
can be used with online book displays, etc. in cases where no
original cover is available.

The cover elements (title, subtitle, creators, etc.) will be sized,
adjusted and possibly wrapped automatically. This works quite well
in most cases, but the result will certainly not be
visually/typographically pleasant in all cases. The package doesn't
currently provide any options for manually adjusting the arrangement
of cover elements.

The package is based on a script by [@kyrretl](https://github.com/kyrretl).

### Installation

Install using Composer:

```bash
composer require scriptotek/covergenerator dev-master
```

The package requires ImageMagick and Ghostscript.

### Usage example

```php

require('vendor/autoload.php');
use Scriptotek\GenericBookCover\BookCover;

$cover = new BookCover();
$cover->setTitle('Manual of scientific illustration')
	->setSubtitle('with special chapters on photography, cover design and book manufacturing')
	->setCreators('Charles S. Papp')
	->setEdition('3rd enl. ed.')
	->setPublisher('American Visual Aid Books')
	->setDatePublished('1976')
	->setRandomBackgroundColor()
	->save('cover1.png');
```

Instead of saving the cover to a file, you can also get the image data and serve it directly:

```php
header('Content-Type: image/png');
echo $cover->getImageBlob();
```


### Example covers

See `examples/examples.php` for the source code for the example covers.

![Cover 1](examples/cover1.png)
![Cover 2](examples/cover2.png)
![Cover 3](examples/cover3.png)
![Cover 4](examples/cover4.png)
