<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = Factory::create("fr-FR");

        //Nous gérons les utilisateurs
        $users = [];

        $genres = ['male', 'female'];

        for ($i = 1; $i < 10; $i++) {
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname)
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setPhone('0645474899')
                ->setIntroduction($faker->sentence(2))
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        //Nous gérons les annonces

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
            $latitude = $faker->latitude($min = 43.53, $max = 43.67);
            $longitude = $faker->longitude($min = 1.35, $max = 1.53);
            $user = $users[mt_rand(0, count($users) - 1)];


            // $slug = $slugify->slugify($title);

            $ad->setTitle($title)
                //   ->setSlug($slug)
                ->setPrice(mt_rand(95000, 399000))
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCoverImage($coverImage)
                ->setRooms(mt_rand(1, 6))
                ->setLatitude($latitude)
                ->setLongitude($longitude)
                ->setCity($city)
                ->setAddress($address)
                ->setPostalCode($postalCode)
                ->setAuthor($user);

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
