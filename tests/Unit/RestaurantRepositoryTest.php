<?php



use App\Models\Address;
use App\Models\Restaurant;
use PHPUnit\Framework\TestCase;
use App\Repository\RestaurantRepository;
use App\Database;

class RestaurantRepositoryTest extends TestCase {
    private $restaurantRepository;
    private $pdoMock;
    private $stmtMock;
    private $databaseMock;

    protected function setUp(): void {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->stmtMock = $this->createMock(PDOStatement::class);
        $this->databaseMock = $this->createMock(Database::class);
        $this->databaseMock->method('connect')->willReturn($this->pdoMock);
        $this->restaurantRepository = new RestaurantRepository($this->databaseMock);
    }

    public function testGetRestaurants() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetchAll')->willReturn([
            [
                'restaurant_id' => 1,
                'name' => 'Test Restaurant',
                'description' => 'Test Description',
                'image' => 'test.jpg',
                'email' => 'test@test.com',
                'phone' => '1234567890',
                'website' => 'http://test.com',
                'address_id' => 1,
                'street' => 'Test Street',
                'city' => 'Test City',
                'postal_code' => '12345',
                'house_no' => '123',
                'apartment_no' => '101',
                'rate' => 4.5,
                'publicate' => true
            ]
        ]);

        $result = $this->restaurantRepository->getRestaurants();
        $this->assertCount(1, $result);
        $this->assertEquals('Test Restaurant', $result[0]->getName());
        $this->assertEquals('Test City', $result[0]->getAddress()->getCity());
    }

    public function testAddRestaurant() {
        $restaurant = new Restaurant(
            null,
            'New Restaurant',
            'A nice place',
            'image.png',
            'http://newrestaurant.com',
            'new@restaurant.com',
            '123456789',
            new Address(null, 'New Street', 'New City', '12345', '10', '100'),
            0,
            true
        );

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->pdoMock->method('lastInsertId')->willReturn('1');

        $restaurantId = $this->restaurantRepository->addRestaurant($restaurant);
        $this->assertEquals(1, $restaurantId);
    }

    public function testGetRestaurant() {
        $expectedRestaurantData = [
            'restaurant_id' => 1,
            'name' => 'Specific Restaurant',
            'description' => 'Specific Description',
            'image' => 'specific.jpg',
            'email' => 'specific@test.com',
            'phone' => '9876543210',
            'website' => 'http://specific.com',
            'address_id' => 1,
            'street' => 'Specific Street',
            'city' => 'Specific City',
            'postal_code' => '54321',
            'house_no' => '321',
            'apartment_no' => '201',
            'rate' => 4.7
        ];

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);
        $this->stmtMock->method('fetch')->willReturn($expectedRestaurantData);

        $restaurant = $this->restaurantRepository->getRestaurant(1);
        $this->assertNotNull($restaurant);
        $this->assertEquals('Specific Restaurant', $restaurant->getName());
    }

    public function testDeleteRestaurant() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);

        $success = $this->restaurantRepository->deleteRestaurant(1);
        $this->assertTrue($success);
    }

    public function testTogglePublication() {
        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('execute')->willReturn(true);

        $success = $this->restaurantRepository->togglePublication(1);
        $this->assertTrue($success);
    }

    public function testUpdateRestaurant() {
        $restaurant = new Restaurant(
            1,
            'Updated Restaurant',
            'Updated Description',
            'updated.jpg',
            'http://updated.com',
            'updated@test.com',
            '9876543210',
            new Address(1, 'Updated Street', 'Updated City', '98765', '123', '202'),
            4.9,
            true
        );

        $this->pdoMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->expects($this->exactly(2))->method('execute')->willReturn(true);

        $success = $this->restaurantRepository->updateRestaurant($restaurant);
        $this->assertTrue($success);
    }
}

