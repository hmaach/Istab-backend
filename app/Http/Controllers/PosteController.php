<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use App\Models\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page');
        $limit = $request->input('limit');

        // Calculate the offset
        $offset = $page * $limit;
        $postes = DB::table('postes')
            ->join('users', 'users.id', '=', 'postes.id_user')
            ->leftJoin('reacts', 'reacts.id_poste', '=', 'postes.id')
            ->select(
                'postes.id as id',
                'users.id as user_id',
                'users.nom',
                'users.prenom',
                'users.role',
                'postes.libelle',
                'postes.type',
                'postes.audience',
                'postes.created_at',
                DB::raw('coalesce(count(reacts.id_poste), 0) as reacts'),
                'reacts.id_user as liked'
            )
            ->groupBy('postes.id', 'reacts.id_user')
            ->orderBy('created_at', 'desc')
            ->get();

        if (Auth::check()) {
            $user = Auth::user();
            foreach ($postes as $post) {
                $post->liked = DB::table('reacts')
                    ->where('id_poste', $post->id)
                    ->where('id_user', $user->id)
                    ->exists();
            }
        }
        return response([
            'postes' => $postes,
        ]);
//            ->header('Access-Control-Allow-Credentials', 'true')
//            ->header('Access-Control-Allow-Origin', 'http://localhost:3000') // replace with your frontend URL
//            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }


    public function likePost(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Poste::find($postId);
        $react = new React();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($request->action === 'like') {
            $react->id_user = $user->id;
            $react->id_poste = $postId;
            $react->save();
        } else if ($request->action === 'dislike') {
            $reactDis = DB::table('reacts')
                ->where('id_user', $user->id)
                ->where('id_poste', $postId)
                ->first();
            if ($reactDis) {
                DB::table('reacts')
                    ->where('id_user','=', $user->id)
                    ->where('id_poste','=', $postId)
                    ->delete();
                return response()->json(['message' => 'React deleted successfully']);
            } else {
                return response()->json(['error' => 'React not found'], 404);
            }
        }
        return response()->json(['message' => 'Post successfully liked/disliked']);
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
        $poste = new Poste();
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
//        return view('updatePoste',[
//            'poste' => $poste
//        ]);
        return response([
            'poste' => $poste
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
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
        $id = $request->input('id');
        $poste = Poste::find($id);
//        dd($poste);
        $poste->delete();
        return redirect()->back();
    }
}
