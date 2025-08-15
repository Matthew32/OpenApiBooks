<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class QueueBookHistory extends Model
{
    final const PENDING_STATUS = 'pending';
    final const ERROR_STATUS = 'error';
    final const SUCCESS_STATUS = 'success';

    protected $table = 'queue_book_histories';

    protected $fillable = ['error_message', 'status', 'payload'];
}
