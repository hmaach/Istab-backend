<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosteRequest;
use App\Models\Filiere;
use App\Models\PDF;
use App\Models\PdfCategorie;
use App\Models\Poste;
use App\Models\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Scalar\String_;

class PosteController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $filieres = [];
            $postes = [];

            if ($user->role === "admin") {
                $filieres = Filiere::all(['id', 'libelle']);
                $postes = DB::table('postes')
                    ->join('users', 'users.id', '=', 'postes.user_id')
                    ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
                    ->leftJoin('filieres', 'filieres.id', '=', 'postes.audience_id')
                    ->leftJoin('p_d_f_s', 'p_d_f_s.poste_id', '=', 'postes.id')
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
                        'filieres.extention as filiere_extention',
                        DB::raw('coalesce(count(reacts.poste_id), 0) as reacts'),
                        'reacts.user_id as liked',
                        'p_d_f_s.path as pdf_path',
                    )
                    ->groupBy('postes.id', 'reacts.user_id','p_d_f_s.path')
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($postes as $post) {
                    $post->liked = DB::table('reacts')
                        ->where('poste_id', $post->id)
                        ->where('user_id', $user->id)
                        ->exists();
                }

                return response([
                    'postes' => $postes,
                    'filieres' => $filieres
                ]);
            } elseif ($user->role === "formateur") {
                $filieres = Filiere::all(['id', 'libelle']);
                $postes = DB::table('postes')
                    ->join('users', 'users.id', '=', 'postes.user_id')
                    ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
                    ->leftJoin('filieres', 'filieres.id', '=', 'postes.audience_id')
                    ->leftJoin('p_d_f_s', 'p_d_f_s.poste_id', '=', 'postes.id')
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
                        'filieres.extention as filiere_extention',
                        DB::raw('coalesce(count(reacts.poste_id), 0) as reacts'),
                        'reacts.user_id as liked',
                        'p_d_f_s.path as pdf_path',
                    )
                    ->groupBy('postes.id', 'reacts.user_id','p_d_f_s.path','p_d_f_s.path')
                    ->where(function ($query) {
                        $query->where('audience', '=', 'public')
                            ->orWhere('audience', '=', 'etablissement')
                            ->orWhere('audience', '=', 'formateurs');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($postes as $post) {
                    $post->liked = DB::table('reacts')
                        ->where('poste_id', $post->id)
                        ->where('user_id', $user->id)
                        ->exists();
                }

                return response([
                    'postes' => $postes,
                    'filieres' => $filieres
                ]);
            } else {
                $postes = DB::table('postes')
                    ->join('users', 'users.id', '=', 'postes.user_id')
                    ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
                    ->leftJoin('filieres', 'filieres.id', '=', 'postes.audience_id')
                    ->leftJoin('p_d_f_s', 'p_d_f_s.poste_id', '=', 'postes.id')
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
                        'filieres.extention as filiere_extention',
                        DB::raw('coalesce(count(reacts.poste_id), 0) as reacts'),
                        'reacts.user_id as liked',
                        'p_d_f_s.path as pdf_path',
                    )
                    ->groupBy('postes.id', 'reacts.user_id','p_d_f_s.path')
                    ->orderBy('created_at', 'desc');

                $postes = $postes->where(function ($query) use ($user) {
                    $query->where('postes.audience', 'public')
                        ->orWhere('postes.audience', 'etablissement')
                        ->orWhere(function ($subquery) use ($user) {
                            $subquery->where('postes.audience', 'filiere')
                                ->whereIn('postes.audience_id', function ($subsubquery) use ($user) {
                                    $subsubquery->select('groupes.filiere_id')
                                        ->from('groupes')
                                        ->where('groupes.id', '=', DB::raw($user->groupe_id));
                                });
                        });
                });


                $postes = $postes->get();

                foreach ($postes as $post) {
                    $post->liked = DB::table('reacts')
                        ->where('poste_id', $post->id)
                        ->where('user_id', $user->id)
                        ->exists();
                }

                return response([
                    'postes' => $postes,
                ]);
            }
        } else {
            $postes = DB::table('postes')
                ->join('users', 'users.id', '=', 'postes.user_id')
                ->leftJoin('reacts', 'reacts.poste_id', '=', 'postes.id')
                ->leftJoin('filieres', 'filieres.id', '=', 'postes.audience_id')
                ->leftJoin('p_d_f_s', 'p_d_f_s.poste_id', '=', 'postes.id')
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
                    'filieres.extention as filiere_extention',
                    DB::raw('coalesce(count(reacts.poste_id), 0) as reacts'),
                    'reacts.user_id as liked',
                    'p_d_f_s.path as pdf_path',
                )
                ->groupBy('postes.id', 'reacts.user_id','p_d_f_s.path')
                ->where(function ($query) {
                    $query->where('audience', '=', 'public');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response([
                'postes' => $postes,
            ]);
        }
    }


    public
    function likePost(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Poste::find($postId);
        $react = new React();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($request->action === 'like') {
            $react->user_id = $user->id;
            $react->poste_id = $postId;
            $react->save();
        } else if ($request->action === 'dislike') {
            $reactDis = DB::table('reacts')
                ->where('user_id', $user->id)
                ->where('poste_id', $postId)
                ->first();
            if ($reactDis) {
                DB::table('reacts')
                    ->where('user_id', '=', $user->id)
                    ->where('poste_id', '=', $postId)
                    ->delete();
                return response()->json(['message' => 'React deleted successfully']);
            } else {
                return response()->json(['error' => 'React not found'], 404);
            }
        }
        return response()->json(['message' => 'Post successfully liked/disliked']);
    }

    public
    function store(PosteRequest $request)
    {
        $poste = new Poste();
        $user = Auth::user();
        if ($user) {
            $pdfPath='';
            $poste->user_id = $user->id;
            $poste->libelle = $request->libelle;
            $poste->type = $request->type;
            $poste->audience = $request->audience;
            $poste->audience_id = $request->audience_id;
            $poste->save();
            if ($request->hasFile('pdf')) {
                $pdf = new PDF();
                $pdfFile = $request->file('pdf');
                $pdfPath = $pdfFile->store('pdfs');
                $pdf->poste_id = $poste->id;
                $pdf->path = $pdfPath;
                if ($request->pdfCategorieId) {
                    $pdf->pdf_categorie_id = $request->pdfCategorieId;
                }
                $pdf->save();
            }
            return response([
                'message' => "success",
                'post_id' => $poste->id,
                'pdf_path'=>$pdfPath,
            ]);
        } else {
            return response([
                'message' => "Vous n'avez pas le droit de publier",

            ]);
        }
    }

    public
    function show(Poste $poste)
    {
        //
    }

    public
    function update(PosteRequest $request)
    {
        $user = Auth::user();
        if ($user) {
            $id = $request->input('id');
            $poste = Poste::find($id);
            if ($poste) {
                $poste->user_id = $request->user_id;
                $poste->libelle = $request->libelle;
                $poste->type = $request->type;
                $poste->audience = $request->audience;
                if ($user->id == $poste->user_id) {
                    $poste->save();
                    return response([
                        'message' => "success"
                    ]);
                } else {
                    return response([
                        'message' => "Vous n'avez pas le droit de toucher ce poste"
                    ]);
                }
            } else {
                return response([
                    'message' => "Poste not found"
                ]);
            }
        } else {
            return response([
                'message' => "not logged in"
            ]);
        }
    }


    public
    function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $id = $request->input('id');
            $poste = Poste::find($id);
            if ($poste) {
                if ($user->id === $poste->user_id || $user->role === "admin") {
                    $poste->delete();
                    return response([
                        'message' => "success"
                    ]);
                } else {
                    return response([
                        'message' => "Vous n'avez pas le droit d'effacer ce poste"
                    ]);
                }
            } else {
                return response([
                    'message' => "Poste not found"
                ]);
            }
        } else {
            return response([
                'message' => "not logged in"
            ]);
        }
    }

}
