<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slug){}
    public function load(ObjectManager $manager): void
    {
            $faker = Faker\Factory::create('fr_FR');
    
            for($prod = 1; $prod <=20; $prod++){
    
                $product = new Products();
                $product->setName($faker->text(15))
                        ->setDescription($faker->text(50))
                        ->setSlug($faker->slug())
                        ->setPrice($faker->numberBetween(1000,999999))
                        ->setStock($faker->numberBetween(0, 50));
                $category = $this->getReference('cat-'.rand(1,8));
                $product->setCategories($category);
                $this->setReference('prod-'.$prod, $product);

                $manager->persist($product);
            }
            $manager->flush();
        }
}
