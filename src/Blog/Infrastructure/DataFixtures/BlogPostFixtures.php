<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure\DataFixtures;

use App\Blog\Application\Entity\BlogPost;
use App\Blog\Application\Repository\BlogPostRepositoryInterface;
use App\Blog\Application\Service\ImageFilenameGeneratorService;
use App\Shared\Common\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class BlogPostFixtures extends Fixture
{
    private BlogPostRepositoryInterface $blogPostRepository;

    public function __construct(BlogPostRepositoryInterface $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        for ($i = 1; $i <= 25; ++$i) {
            $blogPostId = Uuid::generate();
            $blogPost = new BlogPost(
                $blogPostId,
                $faker->realText(random_int(40, 80)),
                $faker->realText(random_int(500, 5000)),
                ImageFilenameGeneratorService::generate($blogPostId->valueString())
            );

            $this->blogPostRepository->add($blogPost);
        }

        $manager->flush();
    }
}
