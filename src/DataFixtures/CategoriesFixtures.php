<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $count = 1;
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('IT', null, $manager);
        $this->createCategory('LapTop', $parent, $manager);
        $this->createCategory('Computer', $parent, $manager);
        $this->createCategory('Hardware', $parent, $manager);

        $parent = $this->createCategory('Tools', null, $manager);
        $this->createCategory('Power Tools', $parent, $manager);
        $this->createCategory('Gardening', $parent, $manager);
        $this->createCategory('Mecanic', $parent, $manager);

        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder('0');
        $manager->persist($category);

        $this->addReference('cat-'.$this->count, $category);
        $this->count++;

        return $category;
    }
}
