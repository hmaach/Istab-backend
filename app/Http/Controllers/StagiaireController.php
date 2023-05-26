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
    public function index(Request $request)
    {
        $id = $request->input("id");
        $stagiaire = User::with('cv')->find($id);
        $stagiaire->interets;
        $stagiaire->groupe;
        $stagiaire->competences;
        $stagiaire->experiences;
        $stagiaire->formations;
        $stagiaire->groupe->filiere;
        return response([
            "stagiaire" => $stagiaire
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if ($user) {
            $stagiaire = User::findOrFail($id);
            $cv = $stagiaire->cv;
            $cv->propos = $request->input('propos');
            $cv->save();

            return response()->json([
                'message' => 'CV updated successfully',
            ]);
        } else {
            return response([
                'message' => "not logged in"
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
}
