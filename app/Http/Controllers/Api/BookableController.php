<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookableIndexResource;
use App\Http\Resources\BookableShowResource;
use Illuminate\Http\Request;
use App\Models\Bookable;

class BookableController extends Controller
{
    public function index()
    {
        // return Bookable::all();
        return BookableIndexResource::collection(
            Bookable::all()
        );
    }

    public function show(Request $request, $id)
    {
        // return Bookable::findOrFail($id);
        return new BookableShowResource(Bookable::findOrFail($id));
    }
}
