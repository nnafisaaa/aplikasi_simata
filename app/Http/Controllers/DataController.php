<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    // Method untuk mengembalikan data dalam format JSON
    public function getData()
    {
        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => [
                'item1' => 'Value 1',
                'item2' => 'Value 2',
            ]
        ]);
    }
}  