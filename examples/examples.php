<?php

require('../vendor/autoload.php');
use Scriptotek\GenericBookCover\BookCover;


$t0 = microtime(true);
$cover = new BookCover();
$cover->setTitle('Manual of scientific illustration')
	->setSubtitle('with special chapters on photography, cover design and book manufacturing')
	->setCreators('Charles S. Papp')
	->setEdition('3rd enl. ed.')
	->setPublisher('American Visual Aid Books')
	->setDatePublished('1976')
	->randomizeBackgroundColor()
	->save('cover1.png', 350);
$dt = microtime(true) - $t0;
echo sprintf("Cover generated in %d msecs\n", round($dt*1000));


$t0 = microtime(true);
$cover = new BookCover();
$cover->setTitle('An Encyclopaedia of the history of technolology')
    ->setCreators('Ian McNeil')
    ->setPublisher('Routledge')
    ->setDatePublished('1990')
    ->randomizeBackgroundColor()
    ->save('cover2.png', 350);
$dt = microtime(true) - $t0;
echo sprintf("Cover generated in %d msecs\n", round($dt*1000));


$t0 = microtime(true);
$cover = new BookCover();
$cover->setTitle('The alcohol textbook')
    ->setSubtitle('a reference for the beverage, fuel and industrial alcohol industries')
    ->setCreators('K. Jacques')
    ->setPublisher('Nottingham University Press')
    ->setDatePublished('1999')
    ->randomizeBackgroundColor()
    ->save('cover3.png', 350);
$dt = microtime(true) - $t0;
echo sprintf("Cover generated in %d msecs\n", round($dt*1000));


$t0 = microtime(true);
$cover = new BookCover();
$cover->setTitle('Linked')
    ->setSubtitle('the new science of networks')
    ->setCreators('Albert-László Barabási')
    ->setPublisher('Perseus Publ.')
    ->setDatePublished('2002')
    ->randomizeBackgroundColor()
    ->save('cover4.png', 350);
$dt = microtime(true) - $t0;
echo sprintf("Cover generated in %d msecs\n", round($dt*1000));


//header('Cache-Control: no-cache, must-revalidate');
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
//header('Content-Type: image/png');
//echo $image;
