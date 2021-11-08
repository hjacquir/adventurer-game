<?php

declare(strict_types=1);

namespace App\Tests\Func\Infrastructure\Doctrine\Repository;

use App\Domain\Model\GpsCoordinates;
use App\Infrastructure\Doctrine\Repository\GpsCoordinatesRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @covers \App\Domain\Model\GpsCoordinates
 */
class GpsCoordinatesRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private GpsCoordinatesRepository $currentTested;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->currentTested = new GpsCoordinatesRepository($this->entityManager, new Logger('test'));
    }

    public function testSave()
    {
        $this->currentTested->save(new GpsCoordinates(1, 1));

        /** @var GpsCoordinates $fetched */
        $fetched = $this->entityManager->getRepository(GpsCoordinates::class)
            ->findOneBy(
                [
                    'latitude' => 1,
                ]
            );

        // we assert that map is persisted in db
        $this->assertSame(1, $fetched->getLatitude());
    }

    public function testGetMaxLatitude()
    {
        $this->currentTested->save(new GpsCoordinates(10, 11));
        $this->currentTested->save(new GpsCoordinates(15, 13));

        $this->assertSame(15, $this->currentTested->getMaxLatitude());
    }

    public function testGetMaxLongitude()
    {
        $this->currentTested->save(new GpsCoordinates(100, 118));
        $this->currentTested->save(new GpsCoordinates(205, 13));

        $this->assertSame(118, $this->currentTested->getMaxLongitude());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
