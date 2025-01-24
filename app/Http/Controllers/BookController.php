<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }

    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $book = Book::create($request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
        ]));

        return response()->json($book, 201);
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);

        return response()->json($book);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update($request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
        ]));

        return response()->json($book);
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json(null, 204);
    }
}