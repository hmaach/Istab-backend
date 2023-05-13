<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StagiaireController extends Controller
{

    public function randomFourStagiaires()
    {
        $stagiaires = DB::table('users')
            ->join('groupes', 'users.id_groupe', '=', 'groupes.id')
            ->join('filieres', 'groupes.id_filiere', '=', 'filieres.id')
            ->select(
                'users.id',
                'users.nom',
                'users.prenom',
                'filieres.libelle as filiere'
            )
            ->whereNotNull('id_groupe')
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
    public function index()
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
