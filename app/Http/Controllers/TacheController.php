<?php

namespace App\Http\Controllers;

use App\Helper\CustomResponse;
use App\Models\Tache;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taches = Tache::with('user')->paginate(10);
        return CustomResponse::buildCorrectResponse($taches);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validators = Validator::make($request->all(),[
            'nom'=>'required|min:5|unique:taches,nom',
            'date_prevue'=>'nullable|date',
            'done'=>'nullable|boolean',
            'description'=>'nullable|min:10'
        ]);
        if($validators->fails()) {
            return CustomResponse::buildValidationResponse($validators);
        }
        try {
            DB::beginTransaction();
            // opération de bd
            $tache = new Tache($request->all());
            $tache->saveOrFail();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return CustomResponse::buildExceptionResponse($th);
        }
        return CustomResponse::buildCorrectResponse($tache);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function show(Tache $tache)
    {
        if($tache->user_id!=Auth::id()) {
            return CustomResponse::buildCustomErrorResponse("Accès non autorisée !");
        }
        return CustomResponse::buildCorrectResponse($tache->load('user'));
    }

    public function findUserTaches(User $user) {
        $taches = $user->taches;
        return CustomResponse::buildCorrectResponse($taches);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tache $tache)
    {
        $validators = Validator::make($request->all(),[
            'nom'=>"required|min:5|unique:taches,nom,".$tache->id,
            'date_prevue'=>'nullable|date',
            'done'=>'nullable|boolean',
            'description'=>'nullable|min:10'
        ]);
        if($validators->fails()) {
            return CustomResponse::buildValidationResponse($validators);
        }
        try {
            DB::beginTransaction();
            // opération de bd
            $tache->updateOrFail($request->all());
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return CustomResponse::buildExceptionResponse($th);
        }
        return CustomResponse::buildCorrectResponse($tache);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tache $tache)
    {
        try {
            DB::beginTransaction();
            // opération de bd
            $tache->deleteOrFail();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return CustomResponse::buildExceptionResponse($th);
        }
        return CustomResponse::buildCorrectResponse(null);
    }
}
