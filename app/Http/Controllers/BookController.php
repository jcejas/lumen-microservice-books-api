<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get All
     *
     * @return void
     */
    public function index()
    {
        $books = Book::all();
        return $this->successResponse($books->toArray());
    }

    /**
     * Create New Book
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|min:1',
            'author_id' => 'required|min:1'
        ])->validate();

        $book = Book::create($request->all());

        return $this->successResponse($book->toArray(), Response::HTTP_CREATED);
    }

    /**
     * Get By Book ID
     *
     * @param integer $book
     * @return void
     */
    public function show(int $book = 0)
    {
        $book = Book::findOrFail($book);
        return $this->successResponse($book->toArray());
    }

    /**
     * Update Book by ID
     *
     * @param Request $request
     * @param integer $book
     * @return void
     */
    public function update(Request $request, int $book)
    {
        Validator::make($request->all(), [
            'title' => 'max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id' => 'min:1'
        ])->validate();

        $book = Book::findOrFail($book);
        $book->fill($request->all());

        if ($book->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();

        return $this->successResponse($book->toArray());
    }

    /**
     * Delete Book by ID
     *
     * @param integer $book
     * @return void
     */
    public function destroy(int $book)
    {
        $book = Book::findOrFail($book);
        $book->delete();

        return $this->successResponse($book->toArray());
    }
}
