<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StagiaireController extends Controller
{

    public function randomFourStagiaires()
    {

        $stagiaires = User::with("groupe.filiere")
            ->whereNotNull('groupe_id')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return response([
            "stagiaires" => $stagiaires
        ]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id)
    {
        $stagiaire = User::with('cv')
            ->findOrFail($id);

        $stagiaire->load('interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere');

        return response([
            "stagiaire" => $stagiaire
        ]);
    }


    public function update(Request $request, string $id)
    {
        $stagiaire = User::findOrFail($id);

        if ($stagiaire) {
            $cv = $stagiaire->cv;

            if ($cv) {
                $cv->propos = $request->input('propos');
                $cv->save();

                return response()->json([
                    'message' => 'CV updated successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'CV not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }







    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function handleSaveProfilePicture(Request $request, string $id)
    {
        $user = Auth::user();
        if ($user) {
            $stagiaire = User::findOrFail($id);

            // Check if a file was uploaded
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');

                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store the file in the storage directory
                Storage::putFileAs('profile_pictures', $file, $filename);

                // Update the user's profile picture field
                $stagiaire->profile_picture = $filename;
                $stagiaire->save();

                return response()->json([
                    'message' => 'Profile picture saved successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'No file uploaded',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Not logged in',
            ]);
        }
    }
}
