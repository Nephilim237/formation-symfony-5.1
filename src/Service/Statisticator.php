<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class Statisticator
{
    private $manager;

    /**
     * Statisticator constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $users = $this->getUsersCount();
        $ads = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        return compact('users', 'ads', 'bookings', 'comments');
    }

    public function getAdsClassification(string $direction)
    {
        return $this->manager->createQuery('
                        SELECT AVG(c.rating) AS note, a.title, a.id, u.firstName, u.lastName, u.picture
                        FROM App\Entity\Comment AS c
                        JOIN c.ad AS a
                        JOIN a.author AS u 
                        GROUP BY a
                        ORDER BY note ' . $direction
                    )
                    ->setMaxResults(5)
                    ->getResult()
        ;
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User AS u')->getSingleScalarResult();
    }

    public function getAdsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad AS a')->getSingleScalarResult();
    }

    public function getBookingsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking AS b')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment AS c')->getSingleScalarResult();
    }
}