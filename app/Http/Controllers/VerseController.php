<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verse;

class VerseController extends Controller
{
    public function getChapter($connection, $book_number, $chapter)
    {
        $verse = new Verse;

        $verse->setConnection($connection);

        $chapter_old = $verse->select('*')->where([
            ['book_number', '=', $book_number],
            ['chapter', '=', $chapter]
        ])->get();
        
        $chapter = [];
        for ($i=0; $i < count($chapter_old) ; ++$i) {
            if (str_contains($connection, "+")){
                $chapter_old[$i]['text'] = json_decode($chapter_old[$i]['text']);
            }
            $chapter[$chapter_old[$i]["verse"]] = $chapter_old[$i];
        }

        return response()->json(['chapter' => $chapter], 200);
    }
}
