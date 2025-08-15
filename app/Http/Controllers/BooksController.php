<?php

namespace App\Http\Controllers;

use App\Jobs\RecordBooks;
use App\Repositories\BookRepository;
use App\Services\OpenApi;
use Illuminate\Http\Request;

final class BooksController extends Controller
{
    public function __construct(private readonly BookRepository $bookRepository)
    {

    }

    public function query(Request $request, OpenApi $openApi)
    {
        try {
            $validation = \Validator::make($request->all(), [
                'page' => 'integer|min:1',
                'limit' => 'integer|min:1',
                'query' => 'required|string',
            ]);

            if ($validation->fails()) {
                return response()->json($validation->errors(), 422);
            }
            $query = $request->get('query');
            $page = $request->get('page', 1);
            $limitPerPage = $request->get('limit', 100);
            $payload = [
                'query' => $query,
                'page' => $page,
                'limitPerPage' => $limitPerPage,
                'offset' => $limitPerPage * ($page - 1)
            ];

            $openApiResponse = $openApi->search($payload);
            $docs = $openApiResponse['docs'] ?? [];

            if (!$docs) {
                throw new \Exception('Book not found.');
            }
            dispatch(resolve(RecordBooks::class, ['booksResponse' => $docs]));

            return response()->json($openApiResponse['docs']);
        } catch (\Throwable $e) {
            return response()->json('Error on query: ' . $e->getMessage(), 500);
        }
    }

    private function getBooks($page, $limit, $query)
    {
        $this->bookRepository->get();
    }
}
