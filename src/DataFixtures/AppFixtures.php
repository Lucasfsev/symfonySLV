<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Customer;
use App\Entity\DrivingLicense;
use App\Entity\Model;
use App\Entity\Option;
use App\Entity\Reservation;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher){
    $this->hasher  = $hasher;

    }
public function load(ObjectManager $manager): void
{
    $types = [
        'Berline', 'Break', 'Coupé', 'Cabriolet', 'Monospace', 'SUV', 'Utilitaire'
    ];
    $typeEntities = [];
    foreach ($types as $type) {
        $vehicleType = new Type();
        $vehicleType->setName($type);
        $typeEntities[] = $vehicleType;
    }

        $manager->flush();
    }
}
