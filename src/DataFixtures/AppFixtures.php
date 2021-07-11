<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\AbstractFixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Factory::create("fr-FR");
        // $slugify = new Slugify();


        for ($i = 1; $i <= 30; $i++) {


            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $postalCode = "31200";
            $address = $faker->address();
            $city = $faker->city();

            // $slug = $slugify->slugify($title);

            $ad->setTitle($title)
                //   ->setSlug($slug)
                ->setPrice(mt_rand(95000, 399000))
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCoverImage($coverImage)
                ->setRooms(mt_rand(1, 6))
                ->setLatitude("43.604")
                ->setLongitude("1.44305")
                ->setCity($city)
                ->setAddress($address)
                ->setPostalCode($postalCode);

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {

                $image = new Image();

                $image->setUrl($faker->ImageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
