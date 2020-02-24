<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;

class BirthdaysController extends Controller
{
    public function index()
    {
        $contacts = request()->user()->contacts()->birthdays()->get();

        return ContactResource::collection($contacts);
    }
}
