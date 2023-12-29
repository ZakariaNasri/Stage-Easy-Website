<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Attestation;
use App\Models\ChefDep;
use App\Models\Departement;
use App\Models\Entreprise;
use App\Models\Faculte;
use App\Models\Notation;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Presence;
use App\Models\Responsable;
use App\Models\Stage;
use App\Models\Certificate;
use App\Models\Universite;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ResponsableController extends Controller
{
    //consulter list des demandes en qttente (tkhmem)
    public function listDemandeAttenteR(Request $request)
    {
        $info = DB::table('STAGE')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->where('id_Resp', $request->id)
            ->select('ETUDIANT.id_Etud')
            ->distinct()
            ->get()
            ->pluck('id_Etud');

        $stage_counts = [];
        foreach ($info as $id_Etud) {
            $count = DB::table('STAGE')
                ->where('id_Etud', $id_Etud)
                ->where('etatResp', 'accepte')
                ->count();
            $stage_counts[$id_Etud] = $count;
        }

        foreach ($stage_counts as $id_Etud => $count) {
            if ($count == 0) {

                return DB::table('STAGE')
                    ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
                    ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
                    ->select('ETUDIANT.id_Etud', 'theme', 'img_Offre', 'nom_Etud', 'pre_Etud', 'id_Stage', 'email', 'STAGE.id_Offre')
                    ->where('etatChef', '=', 'accepte')
                    ->where('etatResp', '=', 'en attente')
                    ->where('id_Resp', $request->id)
                    ->get();
            }
        }
    }

    //consulter list des demandes acceptees
    public function listDemandeAccepteR(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('theme', 'img_Offre', 'nom_Etud', 'pre_Etud', 'email', 'STAGE.id_Offre')
            ->where('etatResp', '=', 'accepte')
            ->where('id_Resp', $request->id)
            ->get();
    }
    //consulter list des demandes refuse
    public function listDemandeRefuseR(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('theme', 'img_Offre', 'nom_Etud', 'pre_Etud', 'email', 'STAGE.id_Offre')
            ->where('etatResp', '=', 'refuse')
            ->where('id_Resp', $request->id)
            ->get();
    }
    public function listStagiaires(request $request)
    {

        $stage = DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->where('RESPONSABLE.id_Resp', $request->id)
            ->value('OFFRE.id_Offre');

        return DB::table('STAGE')
            ->where('id_Offre', $stage)
            ->where('etatResp', '=', 'accepte')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('nom_Etud', 'pre_Etud', 'ETUDIANT.id_Etud', 'STAGE.id_Etud as id_Etud', 'STAGE.id_Stage')
            ->orderby('nom_Etud', 'asc')
            ->get();
    }

    public function listStagiairesPasNoter(request $request)
    {

        $stage = DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->where('RESPONSABLE.id_Resp', $request->id)
            ->value('OFFRE.id_Offre');

        return DB::table('STAGE')
            ->where('id_Offre', $stage)
            ->where('etatResp', '=', 'accepte')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->leftJoin('NOTATION', 'NOTATION.id_Etud', '=', 'ETUDIANT.id_Etud')
            ->whereNull('NOTATION.id_Etud')
            ->select('nom_Etud', 'pre_Etud', 'specialite', 'noteTotale', 'email', 'STAGE.id_Etud as id_Etud', 'STAGE.id_Stage')
            ->orderBy('nom_Etud', 'asc')
            ->get();
    }
    public function listStagiairesNote(request $request)
    {
        $stage = DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->where('RESPONSABLE.id_Resp', $request->id)
            ->value('OFFRE.id_Offre');

        return DB::table('STAGE')
            ->where('id_Offre', $stage)
            ->where('etatResp', '=', 'accepte')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('NOTATION', 'NOTATION.id_Etud', '=', 'ETUDIANT.id_Etud')
            ->select('nom_Etud', 'pre_Etud', 'specialite', 'noteTotale', 'email', 'STAGE.id_Etud as id_Etud', 'STAGE.id_Stage')
            ->orderby('nom_Etud', 'asc')
            ->get();
    }
    public function sesOffre(request $request)
    {

        return DB::table('OFFRE')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->where('RESPONSABLE.id_Resp', $request->id)
            ->select('theme', 'duree', 'contenu', 'datePost', 'nom_Resp', 'pre_Resp', 'showDesc', 'id_Offre')
            ->get();


    }
    // info Resp
    public function InfoRespR(request $request)
    {
        return DB::table('RESPONSABLE')
            ->join(
                'ENTREPRISE',
                'RESPONSABLE.id_ENTREPRISE',
                '=',
                'ENTREPRISE.id_ENTREPRISE'
            )
            ->select(
                'nom_Entreprise',
                'telEntreprise',
                'addr_Entreprise',
                'nom_Resp',
                'pre_Resp',
                'email',
                'img_Resp'
            )
            ->where('RESPONSABLE.id_Resp', '=', $request->id)
            ->get();
    }
    // fonct modifier les info resp
    public function changeInfoRespR(request $request)
    {
        $Resp = RESPONSABLE::where('id_Resp', $request->id)->get();
        // $Resp = json_decode($Resp, true);  //kun ykun lchkl aw hna
        if (Hash::check($request->currentMdps, $Resp[0]['password'])) {
            $ENTREPRISE = DB::table('RESPONSABLE')
                ->where('id_Resp', '=', $request->id)
                ->get()
                ->value("id_Entreprise");

            DB::table('ENTREPRISE')
                ->where('id_Entreprise', '=', $ENTREPRISE)
                ->update([
                    'nom_Entreprise' => $request->nom_Entreprise,
                    'telEntreprise' => $request->telEntreprise,
                    'addr_Entreprise' => $request->addr_Entreprise
                ]);
            RESPONSABLE::where('id_Resp', '=', $request->id)
                ->update([
                    'nom_Resp' => $request->nom_Resp,
                    'pre_Resp' => $request->pre_Resp,
                    'email' => $request->email,
                    "id_Entreprise" => $ENTREPRISE
                ]);


            if ($request->nvMdps != "" && strlen($request->nvMdps) >= 6) {
                // The new password is valid
                RESPONSABLE::where('id_Resp', $request->id)
                    ->update(['password' => Hash::make($request->nvMdps)]);
            }
        } else
            return response()->json(['msg' => 'wrong password']);
        return response()->json(['msg' => 'information updated successfully',]);

    }
    public function refuserDemandeR(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['etatResp' => 'refuse']);

        $id = STAGE::where('id_Stage', '=', $request->id)
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->select('ETUDIANT.id_Etud', 'theme', 'nom_Resp', 'pre_Resp')
            ->get();
        $id = json_decode($id, true);



        Notification::insert(['destinataire' => 'etudiant', 'id_Destinataire' => $id[0]['id_Etud'], 'message' => 'votre demande' . ' ' . $id[0]['theme'] . ' a ete refuse par le responsable' . '' . $id[0]['nom_Resp'] . ' ' . $id[0]['pre_Resp'] . '.']);

        return response()->json(['msg' => 'information updated successfully']);

    }
    public function accepterDemandeR(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['etatResp' => 'accepte']);

        $id = STAGE::where('id_Stage', '=', $request->id)
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->select('ETUDIANT.id_Etud', 'theme', 'nom_Resp', 'pre_Resp')
            ->get();
        $id = json_decode($id, true);



        Notification::insert([
            'destinataire' => 'etudiant',
            'id_Destinataire' => $id[0]['id_Etud'],
            'message' => 'votre demande ' . $id[0]['theme'] . ' a ete acceptee par le responsable' . ' ' . $id[0]['nom_Resp'] . ' ' . $id[0]['pre_Resp'] . '.'
        ]);

        return response()->json(['msg' => 'information updated successfully']);

    }
    public function envoyerMotifR(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['motif' => $request->motif]);
        return response()->json(['msg' => $request->motif]);

    }
    public function noterEtud(Request $request)
    {
        $idEtud = ETUDIANT::where('id_Etud', $request->id)->value('id_Etud');
        $idStage = STAGE::where('id_Etud', $idEtud)->value('id_Stage');

        $totalGrade = $request->attitude + $request->initiative + $request->capacite + $request->connaissance;

        DB::table('NOTATION')
            ->insert([
                'discipline' => $request->discipline,
                'attitude' => $request->attitude,
                'initiative' => $request->initiative,
                'capacite' => $request->capacite,
                'connaissance' => $request->connaissance,
                'noteTotale' => $totalGrade,
                'id_Etud' => $idEtud,
                'id_Stage' => $idStage
            ]);

        return response()->json(['msg' => 'La note totale est : ' . $totalGrade . '.', 'finalNote' => $totalGrade]);
    }
    public function presence(Request $request)
    {
        $idEtud = ETUDIANT::where('id_Etud', $request->id)->value('id_Etud');
        $idStage = STAGE::where('id_Etud', $idEtud)->value('id_Stage');


        DB::table('PRESENCE')
            ->insert([
                'datePresence' => $currentDateTime = Carbon::now()->format('Y-m-d'),
                'heureEntr' => $request->heureE,
                'heureSort' => $request->heureS,
                'id_Etud' => $idEtud,
                'id_Stage' => $idStage
            ]);

        return response()->json(['msg' => 'presence enregistre']);
    }

    public function listPresence(Request $request)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d');

        return DB::table('PRESENCE')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'PRESENCE.id_Etud')
            ->select('datePresence', 'heureEntr', 'heureSort', 'nom_Etud', 'pre_Etud')
            ->where('datePresence', '=', $currentDateTime)
            ->get();
    }

    public function listTousPresence(Request $request)
    {
        $currentDateTime = Carbon::now()->format('Y-m-d');

        return DB::table('PRESENCE')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'PRESENCE.id_Etud')
            ->select('datePresence', 'heureEntr', 'heureSort', 'nom_Etud', 'pre_Etud')
            ->orderBy('datePresence', 'asc')
            ->get();
    }
    public function ajouterOffre(request $request)
    {

        $iden = RESPONSABLE::where('id_Resp', $request->id_Resp)->value('id_Entreprise');
        $currentDateTime = Carbon::now();
        $offre = OFFRE::create([
            'theme' => $request->theme,
            'duree' => $request->duree,
            'datePost' => $currentDateTime,
            'contenu' => $request->contenu,
            'img_Offre' => $request->img,
            "ajouterPar" => "responsable",
            'id_Resp' => $request->id_Resp,
            'id_Entreprise' => $iden
        ]);
        $file = $request->file('img');
        $fileExt = $file->extension();
        $newFileName = 'offre' . $offre->id_Offre . '.' . $fileExt;
        // $path = 'pictures/' . $newFileName;
        $request->file('img')->move(public_path('pictures/'), $newFileName);


        $offre->img_Offre = $newFileName;
        $offre->save();
        // ->update(['img_Offre' => $newFileName]);

        $etudiants = ETUDIANT::all();

        foreach ($etudiants as $etudiant) {
            Notification::insert(['destinataire' => 'etudiant', 'id_Destinataire' => $etudiant->id_Etud, 'message' => 'Vous avez une nouvelle offre !']);
        }
        return response()->json([
            'msg' => 'information inserted successfuly',
        ]);
    }

    public function respNotif(request $request)
    {
        return Notification::select('message', 'timeStamp', 'id_Notification')
            ->where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'responsable')
            ->orderby('timeStamp', 'DESC')
            ->get();
    }
    // nombre de notification pas vu
    public function unseenRespNotifNbr(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'responsable')
            ->where('etat', '=', 'pasVu')
            ->count();
    }

    //nombre de notification deja vu
    public function seeRespNotif(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'responsable')
            ->update(['etat' => 'vu']);
    }

    public function suppOffre(request $request)
    {

        OFFRE::where('id_Offre', $request->id)
            ->delete();

        return response()->json([
            'msg' => 'offre supprimé',
        ]);

    }
    //date poste ne doit pas etre modifiable
    public function modifierOffre(request $request)
    {
        $offre = OFFRE::where('id_Offre', $request->idOffre)
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->update([
                'theme' => $request->theme,
                'duree' => $request->duree,
                'contenu' => $request->contenu,
                'nom_Resp' => $request->nomR,
                'img_Offre' => $request->img,
                'pre_Resp' => $request->preR
            ]);
        $file = $request->file('img');
        $fileExt = $file->extension();
        $newFileName = 'offre' . $request->idOffre . '.' . $fileExt;
        // $path = 'pictures/' . $newFileName;
        $request->file('img')->move(public_path('pictures/'), $newFileName);
        $offre = OFFRE::where('id_Offre', $request->idOffre)->first();
        $offre->img_Offre = $newFileName;
        $offre->save();
        return response()->json([
            'msg' => 'Information updated successfully.',
        ]);
    }
    public function genererPdf(Request $request)
    {
        $array = DB::table('ETUDIANT')
            ->where('ETUDIANT.id_Etud', '=', $request->id_Etud)
            ->join('STAGE', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->join('ENTREPRISE', 'OFFRE.id_Entreprise', '=', 'ENTREPRISE.id_Entreprise')
            ->select([
                'ETUDIANT.id_Etud',
                'nom_Etud',
                'pre_Etud',
                'dateDeb',
                'dateFin',
                'dateNaiss',
                'lieuNaiss',
                'specialite',
                'nom_Resp',
                'pre_Resp',
                'addr_Entreprise',
                'theme',
                'diplome',
                'nom_Entreprise'
            ])
            ->get();
        $array = json_decode($array, true);

        $certificate = Certificate::where('id_Etud', $request->id_Etud)
            ->where('id_Stage', $request->id_Stage)
            ->first();

        if (!$certificate) {
            $certificate = new Certificate();
            $certificate->id_Etud = $request->id_Etud;
            $certificate->id_Stage = $request->id_Stage;
            $certificate->save();
        }

        $data_array = [
            'nom' => $array[0]['nom_Etud'],
            'prenom' => $array[0]['pre_Etud'],
            'theme' => $array[0]['theme'],
            'dateDeb' => $array[0]['dateDeb'],
            'dateFin' => $array[0]['dateFin'],
            'dateNaiss' => $array[0]['dateNaiss'],
            'lieuNaiss' => $array[0]['lieuNaiss'],
            'specialite' => $array[0]['specialite'],
            'diplome' => $array[0]['diplome'],
            'nom_Resp' => $array[0]['nom_Resp'],
            'pre_Resp' => $array[0]['pre_Resp'],
            'addr_Entreprise' => $array[0]['addr_Entreprise'],
            'nom_Entreprise' => $array[0]['nom_Entreprise'],
            'date' => $currentDateTime = Carbon::now()->format('Y-m-d'),
            'token' => $certificate->token,
        ];


        $pdf = PDF::loadView('pdf', $data_array);
        // Output the generated PDF to Browser
        Notification::insert([
            'destinataire' => 'etudiant',
            'id_Destinataire' => $array[0]['id_Etud'],
            'message' => 'votre attestation est prete'
        ]);



        return $pdf->stream();

    }
    public function suppNotif(request $request)
    {
        return Notification::where('id_Notification', '=', $request->idN)
            ->delete();
    }

    public function downloadCV(request $request)
    {
        $etudiant = ETUDIANT::where('id_Etud', '=', $request->id_Etud)->first();
        $File = public_path('cvs/' . $etudiant->cv);
        return Response::download($File);
    }

    public function creeCompte(request $request)
    {

        // si le compte d'etudiant' existe deja

        $etudiantExist = ETUDIANT::where('email', $request->email)->exists();
        if ($etudiantExist) {
            return response()->json(['error' => 'Un compte avec cet e-mail existe déjà.'], 400);
        }
        //si le compte n'existe pas
        $Dep = DB::table('DEPARTEMENT')
            ->where('DEPARTEMENT.nom_Dep', '=', $request->nomD)
            ->value('id_Dep');

        $user = ETUDIANT::create([
            'nom_Etud' => $request->nom,
            'pre_Etud' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->mdps),
            'img_Etud' => $request->img,
            'dateNaiss' => $request->dateN,
            'lieuNaiss' => $request->lieuN,
            'telEtud' => $request->telE,
            'numCarte' => $request->numCarte,
            'diplome' => $request->diplome,
            'specialite' => $request->specialite,
            "id_Dep" => $Dep,
        ]);

        $token = $user->createToken('Token Name')->accessToken;

        return response()->json([
            'msg' => 'account created successfully',
            'token' => $token,
            'user' => $user
        ]);

    }
}
