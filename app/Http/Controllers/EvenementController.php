<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $filieres = [];

        $query = Evenement::select(
            'evenements.id as id',
            'users.id as user_id',
            'users.nom',
            'users.prenom',
            'users.role',
            'evenements.titre as title',
            'evenements.description',
            'evenements.dateDeb as start',
            'evenements.dateFin as end',
            'evenements.timeDeb',
            'evenements.timeFin',
            'evenements.audience',
            'evenements.created_at',
            'filieres.extention as filiere_extention',
        )
            ->join('users', 'users.id', '=', 'evenements.user_id')
            ->leftJoin('filieres', 'filieres.id', '=', 'evenements.audience_id')
            ->orderBy('created_at', 'desc');

        if ($type === 'own' && Auth::check()) {
            $user = Auth::user();
            $query->where('evenements.user_id', $user->id);
        } else {
            if (Auth::check()) {
                $user = Auth::user();
                $filieres = [];

                if ($user->role === "admin") {
                    $filieres = Filiere::all(['id', 'libelle']);
                    $query->where(function ($query) {
                    });
                } elseif ($user->role === "formateur") {
                    $filieres = Filiere::all(['id', 'libelle']);
                    $query->where(function ($query) {
                        $query->where('audience', 'public')
                            ->orWhere('audience', 'etablissement')
                            ->orWhere('audience', 'formateurs');
                    });
                } else {
                    $query->where(function ($query) use ($user) {
                        $query->where('audience', 'public')
                            ->orWhere('audience', 'etablissement')
                            ->orWhere(function ($subquery) use ($user) {
                                $subquery->where('audience', 'filiere')
                                    ->whereIn('audience_id', function ($subsubquery) use ($user) {
                                        $subsubquery->select('groupes.filiere_id')
                                            ->from('groupes')
                                            ->where('groupes.id', '=', $user->groupe_id);
                                    });
                            });
                    });
                }
            } else {
                $query->where('audience', 'public');
            }
        }
        $evenements = $query->get();
        return response([
            'evenements' => $evenements,
            'filieres' => $filieres,
        ]);
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
        $user = Auth::user();
        if (!$user) {
            return response([
                'message' => "Vous n'avez pas le droit d'ajouter une événement",
            ]);
        }
        $event = new Evenement();
        $event->user_id = $user->id;
        $event->titre = $request->titre;
        $event->description = $request->description;
        $event->dateDeb = $request->dateDeb;
        $event->timeDeb = $request->timeDeb;
        $event->dateFin = $request->dateFin;
        $event->timeFin = $request->timeFin;
        $event->audience = $request->audience;
        $event->audience_id = $request->audience_id;
        $event->save();
        return response([
            'message' => "success",
            'event_id' => $event->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        return response([
            "evenement" => $evenement
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evenement $evenement)
    {
        //
    }
}
