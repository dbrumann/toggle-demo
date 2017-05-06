<?php

namespace Tests\AppBundle\Features;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentsFeatureTest extends WebTestCase
{
    /**
     * Client A wants a comments feature.
     *
     * When showing a single post, the user should see comments.
     */
    public function testCommentsAreAccessibleWhenEnabled()
    {
        putenv('FEATURE_COMMENTS_ENABLED=1');
        putenv('FEATURE_COMMENTS_RESTRICTED=0');
        $client = static::createClient();

        $crawler = $client->request('GET', $this->getShowBlogPostUri($client));

        $this->assertEquals(1, $crawler->filter('#main > h3')->count());
    }

    /**
     * Client B does not want a comments feature.
     *
     * When showing a single post, there should be no comments displayed.
     */
    public function testCommentsAreNotVisibleWhenDisabled()
    {
        putenv('FEATURE_COMMENTS_ENABLED=0');
        putenv('FEATURE_COMMENTS_RESTRICTED=0');
        $client = static::createClient();

        $crawler = $client->request('GET', $this->getShowBlogPostUri($client));

        $this->assertEquals(0, $crawler->filter('#main > h3')->count());
    }

    /**
     * Client C wants comments enabled for a specific user group.
     *
     * When showing a single post, the user should not see comments
     * unless they are logged in and have the right permissions.
     */
    public function testCommentsAreNotVisibleWhenFeatureIsRestrictedAndUserIsNotLoggedIn()
    {
        putenv('FEATURE_COMMENTS_ENABLED=1');
        putenv('FEATURE_COMMENTS_RESTRICTED=1');
        $client = static::createClient();

        $crawler = $client->request('GET', $this->getShowBlogPostUri($client));

        $this->assertEquals(0, $crawler->filter('#main > h3')->count());
    }

    /**
     * Client C wants comments enabled for a specific user group.
     *
     * When showing a single post, the user should not see comments
     * unless they are logged in and have the right permissions.
     */
    public function testCommentsAreNotVisibleWhenFeatureIsRestrictedAndUserIsNotAdmin()
    {
        putenv('FEATURE_COMMENTS_ENABLED=1');
        putenv('FEATURE_COMMENTS_RESTRICTED=1');
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'john_user',
            'PHP_AUTH_PW' => 'kitten',
        ]);

        $crawler = $client->request('GET', $this->getShowBlogPostUri($client));

        $this->assertEquals(0, $crawler->filter('#main > h3')->count());
    }

    /**
     * Client C wants comments enabled for a specific user group.
     *
     * When showing a single post, the user should not see comments
     * unless they are logged in and have the right permissions.
     */
    public function testCommentsAreVisibleWhenFeatureIsRestrictedAndUserIsAdmin()
    {
        putenv('FEATURE_COMMENTS_ENABLED=1');
        putenv('FEATURE_COMMENTS_RESTRICTED=1');
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'jane_admin',
            'PHP_AUTH_PW' => 'kitten',
        ]);

        $crawler = $client->request('GET', $this->getShowBlogPostUri($client));

        $this->assertEquals(1, $crawler->filter('#main > h3')->count());
    }

    protected function getShowBlogPostUri(Client $client): string
    {
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');
        $blogPostRepository = $entityManager->getRepository(Post::class);
        /** @var Post $blogPost */
        $blogPost = $blogPostRepository->findOneBy([], ['publishedAt' => 'ASC']);

        return '/en/blog/posts/' . $blogPost->getSlug();
    }
}
