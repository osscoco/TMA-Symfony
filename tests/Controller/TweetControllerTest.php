<?php

namespace App\Test\Controller;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TweetControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TweetRepository $repository;
    private string $path = '/tweet/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Tweet::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tweet index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'tweet[label]' => 'Testing',
            'tweet[likes]' => 'Testing',
            'tweet[date_creation]' => 'Testing',
            'tweet[date_modification]' => 'Testing',
            'tweet[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tweet/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tweet();
        $fixture->setLabel('My Title');
        $fixture->setLikes('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setDate_modification('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Tweet');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Tweet();
        $fixture->setLabel('My Title');
        $fixture->setLikes('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setDate_modification('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'tweet[label]' => 'Something New',
            'tweet[likes]' => 'Something New',
            'tweet[date_creation]' => 'Something New',
            'tweet[date_modification]' => 'Something New',
            'tweet[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/tweet/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLabel());
        self::assertSame('Something New', $fixture[0]->getLikes());
        self::assertSame('Something New', $fixture[0]->getDate_creation());
        self::assertSame('Something New', $fixture[0]->getDate_modification());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Tweet();
        $fixture->setLabel('My Title');
        $fixture->setLikes('My Title');
        $fixture->setDate_creation('My Title');
        $fixture->setDate_modification('My Title');
        $fixture->setUser('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tweet/');
    }
}
