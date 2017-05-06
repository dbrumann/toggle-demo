<?php

namespace Tests\AppBundle\Features;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentsFeatureTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Client A wants a comments feature.
     *
     * When showing a single post, the user should see comments.
     */
    public function testCommentsAreAccessibleWhenEnabled()
    {
        $crawler = $this->client->request('GET', $this->getShowBlogPostUri());

        $this->assertEquals(1, $crawler->filter('#main > h3')->count());
    }

    protected function getShowBlogPostUri(): string
    {
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $blogPostRepository = $entityManager->getRepository(Post::class);
        /** @var Post $blogPost */
        $blogPost = $blogPostRepository->findOneBy([], ['publishedAt' => 'ASC']);

        return '/en/blog/posts/' . $blogPost->getSlug();
    }
}
