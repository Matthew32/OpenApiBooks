<?php

namespace App\Jobs;

use App\Repositories\BookRepository;
use App\Repositories\QueueBookHistoryRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class RecordBooks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private $booksResponse)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(BookRepository $bookRepository, QueueBookHistoryRepository $queueBookHistoryRepository): void
    {
        foreach ($this->booksResponse as $book) {
            $queueBookHistory = null;
            try {
                $queueBookHistory = $queueBookHistoryRepository->startProcessing($book);
                $authorName = $book['author_name'][0] ?? null;
                $bookTitle = $book['title'] ?? null;
                if (empty($authorName)) {
                    throw new \Exception('Author name not found.');
                }
                if (empty($bookTitle)) {
                    throw new \Exception('Book title not found.');
                }
                $bookInstance = $bookRepository->save([
                    'title' => $bookTitle,
                    'authorName' => $authorName
                ]);

                if (!$bookInstance) {
                    $queueBookHistoryRepository->recordError('Error on create book.', $queueBookHistory);
                    continue;
                }
                $queueBookHistoryRepository->recordSuccess($queueBookHistory);
            } catch (\Throwable $e) {
                if ($queueBookHistory) {
                    $queueBookHistoryRepository->recordError($e->getMessage(), $queueBookHistory);
                }
                \Log::error($e->getMessage());
            }
        }
    }
}
