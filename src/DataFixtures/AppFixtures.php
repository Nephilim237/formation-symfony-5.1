<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
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

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');

        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Stephane')
            ->setLastName('Owe Ihiof')
            ->setEmail('uchihastefan91@gmail.com')
            ->setHash($this->encode->encodePassword($adminUser, 'Neph1l1m'))
            ->setPicture('https://pbs.twimg.com/profile_images/988311425637605376/lmgBIpaa.jpg')
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(4)) . '</p>')
            ->addUserRole($adminRole)
        ;

        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];
        for ($i = 0; $i < 10; $i++){
            $avatarUrl = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $genre = $faker->randomElement($genres);

            $avatarUrl .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;

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
                ->setCoverImage($faker->imageUrl(1000,300))
                ->setPrice(mt_rand(90000, 400000))
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($user)
            ;

            for ($j = 0; $j < mt_rand(2, 5); $j++){
                $image = new Image();
                $image->setUrl($faker->imageUrl(1000, 350))
                    ->setCaption($faker->sentence())
                    ->setAd($ad)
                ;

                $manager->persist($image);
            }

            //Gestion des reservations
            for ($j = 0; $j < mt_rand(0, 10); $j++){
                $booking = new Booking();

                $duration = mt_rand(2, 10);
                $booker = $users[mt_rand(0, count($users) - 1)];
                $price = $ad->getPrice() * $duration;
                $startDate = $faker->dateTimeBetween('-3 months');
                $endDate = (clone $startDate)->modify("+$duration days");
                $createdAt = $faker->dateTimeBetween('-6 months');

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setAmount($price)
                    ->setCreatedAt($createdAt)
                    ->setComments($faker->paragraph())
                ;

                $manager->persist($booking);

                //Gestion des commentaires
                if (mt_rand(0, 1)){
                    $comment = new Comment();

                    $comment
                        ->setAuthor($booker)
                        ->setAd($ad)
                        ->setContent($faker->paragraph())
                        ->setRating(mt_rand(1, 5))
                    ;

                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
