<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch books from API.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Http::get('https://openlibrary.org/search.json',['q' => 'the+lord+of+the+rings']);

    }
}
