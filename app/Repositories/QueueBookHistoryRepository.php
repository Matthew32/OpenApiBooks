<?php

namespace App\Repositories;

use App\Models\QueueBookHistory;

final class QueueBookHistoryRepository
{
    public function startProcessing($book)
    {
        $status = QueueBookHistory::PENDING_STATUS;

        $queueBookHistory = QueueBookHistory::create([
            'payload' => json_encode($book),
            'status' => $status
        ]);
        if (!$queueBookHistory) {
            throw new \Exception('Queue Book History error on create.');
        }
        return $queueBookHistory;
    }

    private function changeStatus($queueBookHistoryEntity, $queueBookHistoryStatus)
    {
        $queueBookHistoryEntity->status = $queueBookHistoryStatus;

        return $queueBookHistoryEntity->save();
    }

    public function recordError($errorMessage, QueueBookHistory $queueBookHistoryEntity)
    {
        $queueBookHistoryEntity->error_message = $errorMessage;
        return $this->changeStatus($queueBookHistoryEntity, QueueBookHistory::ERROR_STATUS);
    }

    public function recordSuccess(QueueBookHistory $queueBookHistoryEntity)
    {
        return $this->changeStatus($queueBookHistoryEntity, QueueBookHistory::SUCCESS_STATUS);
    }
}
