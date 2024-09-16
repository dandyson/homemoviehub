<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Person;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PersonController extends Controller
{
    protected $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Auth::user()->people()->with('family')->withCount('videos')->get();

        return Inertia::render('Person/PersonIndex', [
            'people' => $people,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Person/PersonDetails', [
            'families' => Auth::user()->families()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'family' => 'required',
        ]);

        $familyName = $request->input('family');

        if (is_array($request->family)) {
            $familyName = $request->input('family')['name'];
        }

        // Check if the family already exists
        $existingFamily = Family::where('name', $familyName)->first();

        if ($existingFamily) {
            // If the family exists, use its ID
            $familyId = $existingFamily->id;
        } else {
            // If the family doesn't exist, create a new one and use its ID
            $newFamily = new Family(['name' => $familyName, 'user_id' => Auth::id()]);
            $newFamily->save();
            $familyId = $newFamily->id;
        }

        $person = new Person([
            'name' => $request->input('name'),
            'user_id' => Auth::id(),
            'family_id' => $familyId,
        ]);

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
        return Inertia::render('Person/PersonShow', [
            'person' => $person,
            'videos' => $person->videos,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        // Ensure the user can only edit People related to their account
        if (Gate::denies('edit', $person)) {
            abort(403, 'Unauthorized action.');
        }

        $person->load('family');

        return Inertia::render('Person/PersonDetails', [
            'person' => $person,
            'families' => Auth::user()->families()->orderBy('name')->get(['id', 'name']),
            'updateMode' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        // Ensure the user can only update People related to their account
        if (Gate::denies('update', $person)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string',
            'family' => 'required',
        ]);

        $familyName = $request->input('family');

        if (is_array($request->family)) {
            $familyName = $request->input('family')['name'];
        }

        // Check if the family already exists
        $existingFamily = Family::where('name', $familyName)->first();

        if ($existingFamily) {
            // If the family exists, use its ID
            $familyId = $existingFamily->id;
        } else {
            // If the family doesn't exist, create a new one and use its ID
            $newFamily = new Family(['name' => $familyName, 'user_id' => Auth::id()]);
            $newFamily->save();
            $familyId = $newFamily->id;
        }

        $person->update([
            'name' => $request->input('name'),
            'family_id' => $familyId,
        ]);

        $person->save();

        return Inertia::render('Person/PersonShow', [
            'person' => $person,
            'videos' => $person->videos,
            'message' => ['type' => 'Success', 'text' => $person->name . ' updated successfully'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        // Ensure the user can only destroy People related to their account
        if (Gate::denies('destroy', $person)) {
            abort(403, 'Unauthorized action.');
        }

        $person->delete();

        // Optionally, you can return a response or redirect
        return response()->json(['success' => 'Person deleted successfully']);
    }

    public function handleAvatarUpload(Request $request, $personId)
    {
        $person = Person::findOrFail($personId);

        return $this->avatarService->handleAvatarUpload($request, $person);
    }
}
