<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures extends Fixture
{
    const NAMES = [
        ['Sansone', 'Ruggiero'],
        ['Prema', 'Tumicelli'],
        ['Cees', 'Auer'],
        ['Tidir', 'Cary'],
        ['Maia', 'Pražak'],
    ];

    const ISSNS = [
        '1119-023X',
        '1684-5315',
        '1996-0786',
        '1684-5374',
        '1996-0794',
        '2162321X',
        '01896016',
        '15744647',
        '14350645',
        '07174055',
    ];

    const STATUSES = ['new', 'pending'];

    public function load(ObjectManager $manager)
    {

        foreach (self::NAMES as $k => $name) {

            $customer = new Customer();
            $customer->setFirstName($name[0]);
            $customer->setLastName($name[1]);
            $customer->setStatus('approved');
            $customer->setDateOfBirth(date('Y-m-d', rand(-315587330, 946716670)));
            $customer->setCreatedAt(new \DateTime('-1209601 seconds'));
            $customer->setUpdatedAt(new \DateTime('-' . rand(604800, 1209600) . ' seconds')); //2 to 1 weeks ago

            $product = new Product();
            $product->setIssn(self::ISSNS[$k*2]);
            $product->setName('Product ' . rand(0, 10000));
            $product->setStatus(self::STATUSES[rand(0,1)]);
            $product->setCreatedAt($customer->getCreatedAt());
            $product->setUpdatedAt($customer->getUpdatedAt());

            $customer->addProduct($product);

            $product = new Product();
            $product->setIssn(self::ISSNS[$k*2+1]);
            $product->setName('Product ' . rand(0, 10000));
            $product->setStatus(self::STATUSES[rand(0,1)]);
            $product->setCreatedAt($customer->getCreatedAt());
            $product->setUpdatedAt($customer->getUpdatedAt());

            $customer->addProduct($product);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
