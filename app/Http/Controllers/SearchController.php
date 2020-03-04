<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Resources\ContactResource;

class SearchController extends Controller
{
    public function index() {
        $data = \request()->validate([
            'searchTerm' => 'required'
        ]);

        $contacts = Contact::search($data['searchTerm'])
            ->where('user_id', request()->user->id)
            ->get();

        return ContactResource::collection($contacts);
    }
}
