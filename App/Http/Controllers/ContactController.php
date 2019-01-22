<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\GetRequest;
use App\Http\Requests\SaveContactRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param GetRequest $request
     * @return array
     */
    public function index(GetRequest $request)
    {
        $request->validate();
        $query = $request->parseQuery();
        $results = Contact::paginate(
            $query['columns'],
            $query['sortColumn'],
            $query['sortDirection'],
            $query['searchValue'],
            $query['start'],
            $query['perPage'],
            $query['draw']
        );

        return $results;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveContactRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveContactRequest $request)
    {
        $request->validate();
        $contact = Contact::create($request->validated());
        return response()->json($contact, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact $contact
     * @return Contact
     */
    public function show(Contact $contact)
    {
        return $contact;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SaveContactRequest $request
     * @param  \App\Contact $contact
     * @return Contact
     */
    public function update(SaveContactRequest $request, Contact $contact)
    {
        $request->validate();
        $contact->update($request->validated());
        response()->json($contact, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact $contact
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204);
    }
}
