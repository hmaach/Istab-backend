<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\CV;



use App\Models\Photo;

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
            ->find($id);

        if (!$stagiaire) {
            return response([
                "message" => "Stagiaire not found"
            ], 404);
        }

        $stagiaire->load('interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere');

        return response([
            "stagiaire" => $stagiaire
        ]);
    }

    public function show($id)
    {

        $stagiaire = User::with('cv', 'interets', 'groupe', 'competences', 'experiences', 'formations', 'groupe.filiere')
            ->find($id);

        if (!$stagiaire) {
            return response()->json([
                'message' => 'Stagiaire not found'
            ], 404);
        }

        // Pass the retrieved stagiaire data to a view and return the generated CV page
        return view('cv.show', ['stagiaire' => $stagiaire]);
    }




    public function update(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            // Update CV propos
            $cv = $stagiaire->cv;

            if ($cv) {
                $cv->propos = $request->input('propos');
                $cv->save();
            } else {
                return response()->json([
                    'message' => 'CV not found',
                ]);
            }

            // Update competences
            $competences = $request->input('competences');

            if ($competences && is_array($competences)) {
                foreach ($competences as $competenceData) {
                    $competence = $stagiaire->competences()->find($competenceData['id']);

                    if ($competence) {
                        $competence->desc = $competenceData['desc'];
                        $competence->categorie = $competenceData['categorie'];
                        $competence->save();
                    }
                }
            }

            return response()->json([
                'message' => 'CV and competences updated successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ]);
        }
    }


    public function addPropos(Request $request, $id)
    {
        $stagiaire = User::find($id);

        if ($stagiaire) {
            $propos = $request->input('propos');

            // Create a new CV propos
            $cv = new CV();
            $cv->propos = $propos;

            // Save the CV propos
            $stagiaire->cv()->save($cv);

            return response()->json([
                'message' => 'CV propos added successfully',
                'cv' => $cv
            ]);
        } else {
            return response()->json([
                'message' => 'Stagiaire not found',
            ], 404);
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
        $user = User::findOrFail($id);

        if ($user) {
            // Check if a file was uploaded
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');

                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store the file in the storage/app/public/profile_pictures directory
                $path = $file->storeAs('public/profile_pictures', $filename);

                // Create a new photo instance
                $photo = new Photo();
                $photo->user_id = $user->id;
                $photo->path = str_replace("public/", "", $path);
                $photo->save();

                return response()->json([
                    'message' => 'Profile picture saved successfully',
                    'path' => $photo->path, // Return the saved profile picture path
                    'user_id' => $photo->user_id, // Return the user ID associated with the photo
                ]);
            } else {
                return response()->json([
                    'message' => 'No file uploaded',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

}
