<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Book;

class BookController
{

    private Book $book;
    public const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    private const TEST_MESSAGE = 'No Database Connection. This is test.';

    public function __construct(private ?\PDO $connection, private string $requestMethod, private ?int $id)
    {
        $this->book = new Book($this->connection);
    }

    public function processApiRequest(): void
    {
        if ($this->connection === null) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $input = (array)json_decode(file_get_contents('php://input'), true);
            $response['body'] = json_encode([
                'message' => self::TEST_MESSAGE,
                'request_method' => $this->requestMethod,
                'book_id' => $this->id ?? 'not set',
                'payload' => $input,
            ]);
        } else {
            $response = match ($this->requestMethod) {
                self::METHOD_GET => $this->getBook(),
                self::METHOD_POST => $this->createBook(),
                self::METHOD_PUT => $this->updateBook(),
                default => $this->notFoundResponse()
            };
        }


        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getBook(): array
    {
        if ($this->id === null) {
            return $this->notFoundResponse("Error! Provide book_id parameter.");
        }

        $result = $this->book->show($this->id);

        if (isset($result['error'])) {
            return $this->notFoundResponse($result['error']);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);

            return $response;
        }
    }

    private function createBook(): array
    {
        $data = $this->getValidatedRequestContent();

        if (isset($data['errors'])) {
            return $this->badRequestResponse($data['errors']);
        }

        $result = $this->book->store($data);

        if (isset($result['error'])) {
            return $this->badRequestResponse($result['error']);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = $result['success'];

            return $response;
        }
    }

    private function updateBook(): array
    {
        $data = $this->getValidatedRequestContent();

        if (isset($data['errors'])) {
            return $this->badRequestResponse($data['errors']);
        }

        $result = $this->book->update($data, $this->id);

        if (isset($result['error'])) {
            return $this->badRequestResponse($result['error']);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = $result['success'];

            return $response;
        }
    }

    private function getValidatedRequestContent(): array
    {
        $data = (array)json_decode(file_get_contents('php://input'), true);

        $errors = [];

        if ($this->id !== null) {
            $exists = $this->book->show($this->id);

            if (isset($exists['error'])) {
                $errors[] = "Book not found.";
            }
        }

        if (!isset($data['name'])) {
            $errors[] = "Book name is not set";
        }

        if (isset($data['name']) && mb_strlen($data['name'] = trim($data['name'])) < 2) {
            $errors[] = "Book name must be at least 2 characters long.";
        }

        if (!isset($data['author'])) {
            $errors[] = "Author name is not set";
        }

        if (isset($data['author']) && mb_strlen($data['author'] = trim($data['author'])) < 2) {
            $errors[] = "Author name must be at least 2 characters long.";
        }

        if (!empty($errors)) {
            $data['errors'] = implode(" ", $errors);
        }

        return $data;
    }

    private function notFoundResponse(string $error = "Error. Please try later."): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => $error
        ]);

        return $response;
    }

    private function badRequestResponse(string $error): array
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => $error
        ]);

        return $response;
    }
}