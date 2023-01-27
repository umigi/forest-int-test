<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    const BOOKS = [
        "566041ce-506e-44ef-9cbb-9be493fa230e" => [
            "bookId" => "566041ce-506e-44ef-9cbb-9be493fa230e",
            "bookTitle" => "Node.JS For Dummies",
            "author" => "JK Rowling",
            "costPrice" => 30000,
            "price" => 35000,
            "priceCurrency" => "IDR",
            "currencySign" => "Rp",
            "quantity" => 10
        ],
        "0cee6169-ce6b-4d20-a87f-50144ae6a0ee" => [
            "bookId" => "0cee6169-ce6b-4d20-a87f-50144ae6a0ee",
            "bookTitle" => "PHP For Dummies",
            "author" => "JK Rowling",
            "costPrice" => 30000,
            "price" => 35000,
            "priceCurrency" => "IDR",
            "currencySign" => "Rp",
            "quantity" => 0
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Get some detail of Requested Book
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBook($id)
    {
        // Request CheckStock Endpoint
        $checkStockRoute = route('checkStock', ['id' => $id]);
        $response = Http::post($checkStockRoute,[]);

        // Decode response and if Response Out of stock or Not found, forward Response
        $response_body = json_decode($response->body(), true);
        if (array_key_exists('status', $response_body)) {
            return response()->json($response_body);
        }

        // Request Get Book Endpoint
        $getBookRoute = route('getBook', ['id' => $id]);
        $response = Http::post($getBookRoute,[]);
        
        // Decode response
        $response_body = json_decode($response->body(), true);

        // Adjust Response
        $book = [
            "bookId" => $response_body['bookId'],
            "price" => $response_body['price'],
            "displayPrice" => $response_body['currencySign'].". ".$response_body['price'],
            "madeBy" => $response_body['author']
        ];

        // Return Book
        return response()->json($book);
    }


     /**
     * Display All Detail of Requested Book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBook($id)
    {
        // Get Requested Book
        $books = self::BOOKS;
        $book = $books[$id];

        // Remove Quantity from Response
        unset($book["quantity"]);

        // Return Book Detail
        return response()->json($book);
        
    }

     /**
     * Check Stock of Requested Book.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkStock($id)
    {
        // Get Books Const
        $books = self::BOOKS;
        
        // Return Not Found if Book not exist
        if (!array_key_exists($id,$books)) {
            
            return response()->json([
                "status" => "111",
                "description" => "Book ID not found"
            ]);

        }

        $book = $books[$id];
        
        // Return Out Of Stock if Book Qty is empty
        if ($book['quantity'] < 1) {
            return response()->json([
                "status" => "222",
	            "description" => "Out of stock"
            ]);
        }

        return response()->json([
            "bookTitle" => $book['bookTitle'],
	        "quantity" => $book['quantity']
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
