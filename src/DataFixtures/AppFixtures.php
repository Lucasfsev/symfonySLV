<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Customer;
use App\Entity\DrivingLicense;
use App\Entity\Model;
use App\Entity\Option;
use App\Entity\Reservation;
use App\Entity\State;
use App\Entity\Status;
use App\Entity\Type;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $types = [
            'Berline', 'Break', 'Coupé', 'Cabriolet', 'Monospace', 'SUV', 'Utilitaire', 'Van', 'Minibus'
        ];
        $typeEntities = [];
        foreach ($types as $type) {
            $vehicleType = new Type();
            $vehicleType->setName($type);
            $typeEntities[] = $vehicleType;
        }

        $brands = [
            'BMW' => [
                'Serie 1',
                'Serie 3',
                'Serie 5',
                'X5',
                'Z4'
            ],
            'Audi' => [
                'A1',
                'A3',
                'A4',
                'A6',
                'Q3',
                'Q5',
                'Q7'
            ],
            'Mercedes' => [
                'Classe A',
                'Classe C',
                'GLA',
                'GLE',
                'Sprinter',
                'Vito'
            ],
            'Tesla' => [
                'Model S',
                'Model 3',
                'Model X',
                'Model Y',
                'Cybertruck'
            ],
            'Volkswagen' => [
                'Polo',
                'Golf',
                'Passat',
                'Tiguan',
                'Touareg',
                'Transporter'
            ]
        ];

        $brandEntities = [];
        foreach ($brands as $brand => $models) {
            $brandEntity = new Brand();
            $brandEntity->setName($brand);
            $brandEntities[] = $brandEntity;
            foreach ($models as $model) {
                $modelEntity = new Model();
                $modelEntity->setName($model);
                $modelEntity->setBrand($brandEntity);
                $modelEntities[] = $modelEntity;
            }
        }

        $options = [
            'Climatisation',
            'Toit ouvrant',
            'Caméra de recul',
            'GPS intégré',
            'Sièges chauffants',
            'Volant chauffant',
            'Système audio premium',
            'Détecteur d’angles morts',
            'Système de freinage d’urgence',
        ];
        $optionEntities = [];
        foreach ($options as $option) {
            $vehicleOption = new Option();
            $vehicleOption->setName($option);
            $optionEntities[] = $vehicleOption;
        }

        $vehicleEntities = [];
        foreach ($modelEntities as $model) {
            $vehicle = new Vehicle();
            $vehicle->setModel($model);
            $vehicle->setType($typeEntities[random_int(0, count($typeEntities) - 1)]);
            $vehicle->setPrice(random_int(45, 500));
            $vehicle->setNumberKilometers(random_int(0, 300000));
            $vehicle->setNumberPlate(sprintf('%s-%s-%s', chr(random_int(65, 90)), random_int(100, 999), chr(random_int(65, 90))));
            $vehicle->setYearOfVehicle(random_int(1980, 2024));
            $vehicle->setCapacity(random_int(2, 7));
            $vehicle->setPicturePath('/images/vehicle' . random_int(1, 9) . '.jpg');
            $vehicle->addOption($optionEntities[random_int(0, count($optionEntities) - 1)]);
            $vehicleEntities[] = $vehicle;
        }

        $customers = [
            [
                'lastName' => 'Dupont',
                'firstName' => 'Jean',
                'address' => '123 Rue de Paris',
                'postCode' => '75001',
                'city' => 'Paris',
                'email' => 'jean.dupont@example.com',
                'phone' => '0123456789',
                'drivingLicense' => 'Permis Poids Lourd',
                'roles' => 'user',
                'reservations' => []
            ],
            [
                'lastName' => 'Martin',
                'firstName' => 'Marie',
                'address' => '456 Avenue des Champs',
                'postCode' => '75008',
                'city' => 'Paris',
                'email' => 'marie.martin@example.com',
                'phone' => '0987654321',
                'drivingLicense' => 'Permis Poids Lourd',
                'roles' => 'user',
                'reservations' => []
            ],
            [
                'lastName' => 'Leclerc',
                'firstName' => 'Paul',
                'address' => '789 Boulevard Saint-Germain',
                'postCode' => '75005',
                'city' => 'Paris',
                'email' => 'paul.leclerc@example.com',
                'phone' => '0147258369',
                'drivingLicense' => 'Permis b',
                'roles' => 'user',
                'reservations' => []
            ],
            [
                'lastName' => 'Lemoine',
                'firstName' => 'Sophie',
                'address' => '321 Rue Victor Hugo',
                'postCode' => '69001',
                'city' => 'Lyon',
                'email' => 'sophie.lemoine@example.com',
                'phone' => '0623547891',
                'drivingLicense' => 'Permis b',
                'roles' => 'user',
                'reservations' => []
            ],
            [
                'lastName' => 'Durand',
                'firstName' => 'Luc',
                'address' => '12 Rue de la République',
                'postCode' => '13001',
                'city' => 'Marseille',
                'email' => 'client@client.com',
                'phone' => '0701122334',
                'drivingLicense' => 'Permis b',
                'roles' => 'user',
                'reservations' => []
            ]
        ];

        $customerEntities = [];
        foreach ($customers as $customer) {
            $customerEntity = new Customer();
            $customerEntity->setLastName($customer['lastName']);
            $customerEntity->setFirstName($customer['firstName']);
            $customerEntity->setAddress($customer['address']);
            $customerEntity->setPostCode($customer['postCode']);
            $customerEntity->setCity($customer['city']);
            $customerEntity->setEmail($customer['email']);
            $customerEntity->setPhone($customer['phone']);
            $customerEntity->setDrivingLicense($customer['drivingLicense']);
            $customerEntity->setRoles([$customer['roles']]);
            $customerEntity->setPassword($this->hasher->hashPassword($customerEntity, 'password'));
            $customerEntities[] = $customerEntity;
        }

        $admin = new Customer();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setAddress('random address');
        $admin->setPostCode('123456');
        $admin->setCity('Random city');
        $admin->setEmail('admin@admin.com');
        $admin->setPhone('123456789');
        $admin->setDrivingLicense('Permis b');
        $admin->setRoles(['Admin', 'User']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $customerEntities[] = $admin;

        $states = [
            'En cours de validation', 'Réservation validée', 'Réservation annulée'
        ];
        $stateEntities = [];
        foreach ($states as $state) {
            $stateEntity = new State();
            $stateEntity->setStatus($state);
            $stateEntities[] = $stateEntity;
        }

        $reservations = [
            ['2022-01-01', '2022-01-02', 'en attente'],
            ['2022-01-03', '2022-01-04', 'en attente'],
            ['2022-01-05', '2022-01-06', 'en attente'],
            ['2022-01-07', '2022-01-08', 'en attente'],
            ['2022-02-01', '2022-02-02', 'confirmée'],
            ['2022-02-03', '2022-02-04', 'confirmée'],
            ['2022-03-01', '2022-03-02', 'annulée'],
            ['2022-03-03', '2022-03-04', 'en attente'],
            ['2022-04-01', '2022-04-02', 'confirmée'],
            ['2022-04-03', '2022-04-04', 'en attente']
        ];

        $reservationsEntities = [];
        foreach ($reservations as $reservation) {
            $reservationEntity = new Reservation();
            $reservationEntity->setDateStart(new \DateTimeImmutable($reservation[0]));
            $reservationEntity->setDateEnd(new \DateTimeImmutable($reservation[1]));
            $reservationEntity->setCustomer($customerEntities[random_int(0, count($customerEntities) - 1)]);
            $reservationEntity->setVehicle($vehicleEntities[random_int(0, count($vehicleEntities) - 1)]);
            $reservationEntity->setReference('REF-' . strtoupper(uniqid()));
            $reservationEntity->setState($stateEntities[0]);
            $startDate = new \DateTimeImmutable($reservation[0]);
            $endDate = new \DateTimeImmutable($reservation[1]);
            $numberRentalDay = $startDate->diff($endDate)->days;
            $reservationEntity->setNumberRentalDay($numberRentalDay);
            $vehicle = $reservationEntity->getVehicle();
            $vehiclePrice = $vehicle->getPrice();
            $totalCost = $vehiclePrice * $numberRentalDay;
            $reservationEntity->setTotalCost($totalCost);
            $reservationsEntities[] = $reservationEntity;

            $manager->persist($reservationEntity);
        }

        foreach ($typeEntities as $type) {
            $manager->persist($type);
        }

        foreach ($brandEntities as $brand) {
            $manager->persist($brand);
        }

        foreach ($modelEntities as $model) {
            $manager->persist($model);
        }

        foreach ($optionEntities as $option) {
            $manager->persist($option);
        }

        foreach ($vehicleEntities as $vehicle) {
            $manager->persist($vehicle);
        }

        foreach ($customerEntities as $customer) {
            $manager->persist($customer);
        }

        foreach ($stateEntities as $state) {
            $manager->persist($state);
        }

        foreach ($reservationsEntities as $reservation) {
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
