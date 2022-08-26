<?php

declare(strict_types=1);

namespace App\Model;

class Book
{
    private $dbConn;
    private const TABLE_BOOKS = 'books';

    public function __construct(?\PDO $conn)
    {
        $this->dbConn = $conn;
    }

    public function show(int $id): array
    {
        try {
            $query = "SELECT * FROM " . self::TABLE_BOOKS . " where id = :id";
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $book = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (empty($book)) {
                throw new \Exception("Book not found!");
            }
            $result['success'] = $book;
        } catch (\PDOException|\Exception $e) {
            error_log($e->getMessage());
            $result['error'] = "Failed to get book details. Please try later";
        } finally {
            return $result;
        }
    }

    public function store(array $data): array
    {
        try {
            $currentDateTime = $this->getCurrentDateTime();
            $query = "INSERT INTO " . self::TABLE_BOOKS . "(name, author, created, updated) 
                        VALUES (:name, :author, :created, :updated)";
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':author', $data['author']);
            $stmt->bindParam(':created', $currentDateTime);
            $stmt->bindParam(':updated', $currentDateTime);
            $stmt->execute();
            $result['success'] = "Book has been stored.";
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $result['error'] = 'Book not stored. Please try later.';
        } finally {
            return $result;
        }
    }

    public function update(array $data, ?int $id): array
    {
        try {
            if ($id === null) {
                throw new \Exception("Book id not set.");
            }

            $currentDateTime = $this->getCurrentDateTime();
            $query = "UPDATE " . self::TABLE_BOOKS .
                " SET name = :name, author = :author, updated = :updated
                     WHERE id = :id";
            $stmt = $this->dbConn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':author', $data['author']);
            $stmt->bindParam(':updated', $currentDateTime);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result['success'] = "Book has been updated.";
        } catch (\PDOException|\Exception $e) {
            error_log($e->getMessage());
            $result['error'] = 'Book not updated. Please try later.';
        } finally {
            return $result;
        }
    }

    private function getCurrentDateTime(): string
    {
        $currentDateTime = new \DateTime('now', new \DateTimeZone('Europe/Belgrade'));

        return $currentDateTime->format('Y-m-d H:i:s');
    }
}