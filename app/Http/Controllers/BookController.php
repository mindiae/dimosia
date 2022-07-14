<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Verse;

class BookController extends Controller
{
    public function index($connection)
    {
        $book = new Book;
        $verse = new Verse;

        $book->setConnection($connection);
        $verse->setConnection($connection);

        $books_old = $book->select('*')->get();
        $books = [];
        for ($i=0; $i < count($books_old) ; ++$i) {
            $books[$books_old[$i]["book_number"]] = $books_old[$i];
        }
        
        $maxChapters_old = $verse
            ->selectRaw('`book_number`, max(`chapter`) as max_chapter')
            ->groupBy('book_number')
            ->get();
        
        $maxChapters = [];
        for ($i=0; $i < count($maxChapters_old) ; ++$i) {
            $maxChapters[$maxChapters_old[$i]["book_number"]] = $maxChapters_old[$i];
        }

        return response()->json(['books' => $books, 'maxChapters' => $maxChapters], 200);
    }
}
