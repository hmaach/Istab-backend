<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;

class PosteController extends Controller
{
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
        $poste = New Poste();
        $poste->id_user = $request->id_user;
        $poste->libelle = $request->libelle;
        $poste->type = $request->type;
        $poste->audience = $request->audience;
        $poste->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Poste $poste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $poste = Poste::find($id);
        return view('updatePoste',[
            'poste' => $poste
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id =$request->input('id');
        $poste = Poste::find($id);
        $poste->id_user = $request->id_user;
        $poste->libelle = $request->libelle;
        $poste->type = $request->type;
        $poste->audience = $request->audience;
        $poste->update();
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id =$request->input('id');
        $poste = Poste::find($id);
//        dd($poste);
        $poste->delete();
        return redirect()->back();
    }
}
