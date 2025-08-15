<?php

namespace App\Repositories;

use App\Models\Book;

final class BookRepository
{
    public function save(array $book)
    {
        $payload = [
            'author_name' => $book['authorName'],
            'title' => $book['title'],
        ];

        $book = Book::create($payload);
        if (!$book) {
            throw new \Exception('Book not saved');
        }
        return $book;
    }

    public function get($page = 1, $limit = 10, $query)
    {
        $offset = ($page - 1) * $limit;

        $booksQuery = Book::where('title', 'like', "%$query%")
            ->skip($offset)
            ->take($limit);
        if ($booksQuery->count() > 0) {
            return [];
        }
        return $booksQuery->get()->toArray();
    }
}
