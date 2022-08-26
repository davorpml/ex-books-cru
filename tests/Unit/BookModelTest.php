<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Model\Book;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class BookModelTest extends TestCase
{
    public function test_get_book_from_db(): void
    {
        $database = new Database();
        $dbConn = $database->getConnection();
        $book = new Book($dbConn);

        $actual = $book->show(100000);

        $expected = [
            'error' => 'Failed to get book details. Please try later',
        ];

        $this->assertEquals($expected, $actual);

    }
}