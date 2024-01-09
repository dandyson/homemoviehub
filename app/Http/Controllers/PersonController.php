<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\AvatarService;

class PersonController extends Controller
{
    protected $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function handleAvatarUpload(Request $request, $personId)
    {
        $person = Person::findOrFail($personId);

        return $this->avatarService->handleAvatarUpload($request, $person);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::all();

        return response()->json(['people' => $people]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Person/PersonDetails');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $person = new Person([
            'name' => $request->input('name'),
            'family' => 'Dyson',
        ]);

        if ($request->avatar) {
            $person->avatar = $this->defaultAvatar;
        }

        $person->save();

        return response()->json([
            'person' => $person,
            'message' => ['type' => 'Success', 'text' => 'Person created successfully'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        return Inertia::render('Person/View', [
            'person' => $person,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        return Inertia::render('Person/PersonDetails', [
            'person' => $person,
            'updateMode' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
    
        // Update video with extracted YouTube video ID
        $person->update([
            'name' => $request->input('name'),
        ]);
        
        $person->save();

        return Inertia::render('Person/PersonShow', [
            'person' => $person,
            'message' => ['type' => 'Success', 'text' => $person->name . ' updated successfully'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        //
    }
}
