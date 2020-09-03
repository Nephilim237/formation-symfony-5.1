<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encode;

    public function __construct(UserPasswordEncoderInterface $encode)
    {

        $this->encode = $encode;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $users = [];
        $genres = ['male', 'female'];
        for ($i = 0; $i < 10; $i++){
            $avatarUrl = 'https://randomuser.me/api/protraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $genre = $faker->randomElement($genres);

            $avatarUrl .= ($genre == 'male' ? 'men/' : 'female/') . $avatarId;

            $user = new User();

            $hash = $this->encode->encodePassword($user, 'Neph1l1m');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($avatarUrl)
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 30; $i++){
            $title = $faker->sentence();
            $user = $users[mt_rand(0, count($users) - 1)];

            $ad = new Ad();
            $ad->setTitle($title)
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setCoverImage($faker->imageUrl(1000,350))
                ->setPrice(mt_rand(90000, 400000))
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($user)
            ;

            for ($j = 0; $j < mt_rand(2, 5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad)
                ;

                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
