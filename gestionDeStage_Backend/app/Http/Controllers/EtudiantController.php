<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Attestation;
use App\Models\Chef;
use App\Models\Departement;
use App\Models\Entreprise;
use App\Models\Faculte;
use App\Models\Notation;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Presence;
use App\Models\Responsable;
use App\Models\Stage;
use App\Models\Universite;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EtudiantController extends Controller
{
    //cree compte (hdi teni khdma mch ndifa 100% )

    public function creeCompte(request $request)
    {

        // si le compte d'etudiant' existe deja

        $etudiantExist = ETUDIANT::where('email', $request->email)->exists();
        if ($etudiantExist) {
            return response()->json(['error' => 'Un compte avec cet e-mail existe déjà.'], 400);
        }
        //si le compte n'existe pas
        $Dep = DB::table('DEPARTEMENT')
            ->where('DEPARTEMENT.nom_Dep', '=', $request->nom_Dep)
            ->value('id_Dep');

        $user = ETUDIANT::create([
            'nom_Etud' => $request->nom_Etud,
            'pre_Etud' => $request->pre_Etud,
            'email' => $request->email,
            'password' => Hash::make($request->mdps),
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

    // voir ses informations
    public function InfoEtud(request $request)
    {
        return DB::table('ETUDIANT')
            ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'ETUDIANT.id_Dep')
            ->join('FACULTE', 'FACULTE.id_Fac', '=', 'DEPARTEMENT.id_Fac')
            ->join('UNIVERSITE', 'FACULTE.id_Univ', '=', 'UNIVERSITE.id_Univ')
            ->select(
                'nom_Etud',
                'pre_Etud',
                'email',
                'img_Etud',
                'dateNaiss',
                'lieuNaiss',
                'telEtud',
                'numCarte',
                'diplome',
                'specialite',
                'nom_Dep',
                'nom_Fac'
            )
            ->where('id_Etud', '=', $request->id)
            ->get();
    }

    //modifier ses informations
    public function updateInfoEtud(request $request)
    {

        $Etud = Etudiant::where('id_Etud', $request->id)->get();
        // $Etud = json_decode($Resp, true);
        if (Hash::check($request->currentMdps, $Etud[0]['password'])) {
            Etudiant::where('id_Etud', $request->id)
                ->update([
                    'nom_Etud' => $request->nom,
                    'pre_Etud' => $request->prenom,
                    'email' => $request->email,
                    // 'img_Etud' => $request->img_Etud,
                    'dateNaiss' => $request->dateN,
                    'lieuNaiss' => $request->lieuN,
                    'telEtud' => $request->telE,
                    'numCarte' => $request->numCarte,
                    'diplome' => $request->diplome,
                    'specialite' => $request->specialite
                ]);

            if ($request->nvMdps != "" && strlen($request->nvMdps) >= 6) {
                // The new password is valid
                Etudiant::where('id_Etud', $request->id)
                    ->update(['password' => Hash::make($request->nvMdps)]);
            }
        } else
            return response()->json(['msg' => 'wrong password']);
        return response()->json(['msg' => 'information updated successfully',]);
    }

    //postuler un offre
    public function postulerOffre(Request $request)
    {
        $stg = STAGE::where('id_Etud', '=', $request->idEtud)
            ->where('etatChef', '=', 'accepte')
            ->where('etatResp', '=', 'accepte')
            ->where('dateFin', '>', Carbon::now())
            ->first();

        if ($stg) {
            return response()->json([
                'msg' => 'Cher utilisateur, vous êtes déjà accépté dans un stage .
                                    Veuillez attendre la fin de ce stage pour pouvoir postuler à nouveau.'
            ]);
        }

        $duree = DB::table('OFFRE')
            ->select('duree')
            ->where('id_Offre', '=', $request->idOffre)
            ->first(); // Utilisez first() pour obtenir un seul enregistrement

        $dateDeb = Carbon::parse($request->dateD);
        $dateFin = $dateDeb->copy()->addDays($duree->duree); // Ajoutez la durée à la date de début

        DB::table('STAGE')
            ->insert([
                'dateDeb' => $dateDeb,
                'dateFin' => $dateFin,
                'etatChef' => 'en attente',
                'id_Offre' => $request->idOffre,
                'id_Etud' => $request->idEtud,

            ]);

        $info = DB::table('ETUDIANT')
            ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'ETUDIANT.id_Dep')
            ->join('CHEFDEPARTEMENT', 'CHEFDEPARTEMENT.id_Dep', '=', 'DEPARTEMENT.id_Fac')
            ->where('id_Etud', '=', $request->idEtud)
            ->select('id_ChefDep', 'nom_Etud', 'pre_Etud')
            ->get();
        $info = json_decode($info, true);


        Notification::insert([
            'destinataire' => 'chefDepartement',
            'id_Destinataire' => $info[0]['id_ChefDep'],
            'message' => 'vous avez une nouvelle demande de ' .
            $info[0]['nom_Etud'] . ' ' . $info[0]['pre_Etud'] . '.'
        ]);


        return response()->json(['msg' => 'Votre demande a était bien envoye']);
    }

    //creer une demande quand l'offre n'existe pas
    public function creerDemande(request $request)
    {

        $stg = STAGE::where('id_Etud', '=', $request->idEtud)
            ->where('etatChef', '=', 'accepte')
            ->where('etatResp', '=', 'accepte')
            ->where('dateFin', '>', Carbon::now())
            ->first();

        if ($stg) {
            return response()->json([
                'msg' => 'Cher utilisateur, vous êtes déjà accépté dans un stage .
                                    Veuillez attendre la fin de ce stage pour pouvoir postuler à nouveau.'
            ]);
        }

        // verification de l'existence d'une entreprise
        $entrepriseExist = ENTREPRISE::where('nom_Entreprise', $request->entrName)->exists();
        if ($entrepriseExist) {
            $entreprise = ENTREPRISE::where('nom_Entreprise', $request->entrName)->first();
            $entrepriseId = $entreprise->id_Entreprise;
        } else {
            $entrepriseId = DB::table('ENTREPRISE')
                ->insertGetId([
                    'nom_Entreprise' => $request->entrName,
                    'addr_Entreprise' => $request->adrs,
                    'telEntreprise' => $request->tel
                ]);
        }

        $idRes = DB::table('RESPONSABLE')
            ->insertGetId([
                'nom_Resp' => $request->resLastName,
                'pre_Resp' => $request->resFirstName,
                'email' => $request->resEmail,
                'id_Entreprise' => $entrepriseId
            ]);
        $currentDateTime = Carbon::now();
        $offreId = DB::table('OFFRE')
            ->insertGetId([
                'theme' => $request->theme,
                'duree' => $request->duree,
                'datePost' => $currentDateTime,
                'id_Entreprise' => $entrepriseId,
                'id_Resp' => $idRes,
                'ajouterPar' => 'etudiant'
            ]);

        $dateDeb = Carbon::parse($request->dateD);
        $duree = intval($request->duree);
        $dateFin = $dateDeb->copy()->addDays($duree);
        DB::table('STAGE')
            ->insert([
                'dateDeb' => $dateDeb,
                'dateFin' => $dateFin,
                'etatChef' => 'en attente',
                'id_Etud' => $request->idEtud,
                'id_Offre' => $offreId
            ]);

        $info = DB::table('ETUDIANT')
            ->where('id_Etud', $request->idEtud)
            ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'ETUDIANT.id_Dep')
            ->join('CHEFDEPARTEMENT', 'CHEFDEPARTEMENT.id_Dep', '=', 'DEPARTEMENT.id_Fac')
            ->select('id_ChefDep', 'nom_Etud', 'pre_Etud')
            ->get();
        $info = json_decode($info, true);


        Notification::insert([
            'destinataire' => 'chefDepartement',
            'id_Destinataire' => $info[0]['id_ChefDep'],
            'message' => 'vous avez une nouvelle demande de ' .
            $info[0]['nom_Etud'] . ' ' . $info[0]['pre_Etud'] . '.'
        ]);

        return response()->json([
            'msg' => 'information inserted successfuly',
        ]);
    }


    //modifier demande
//9dr nzido notification teni
    public function modifierDemande(request $request)
    {
        $etatChef = STAGE::where('id_Stage', $request->id)
            ->value('etatChef');
        $etatRes = STAGE::where('id_Stage', $request->id)
            ->value('etatResp');

        $motif = STAGE::where('id_Stage', $request->id)
            ->value('motif');
        if ($etatChef != 'accepte' || ($etatRes === 'refuse' && $motif !== NULL)) {

            $idOffre = STAGE::where('id_Stage', $request->id)
                ->value('id_Offre');
            $idres = OFFRE::where('id_Offre', $idOffre)
                ->value('id_Resp');
            $idEnt = OFFRE::where('id_Offre', $idOffre)
                ->value('id_Entreprise');
            $ajouterPar = OFFRE::where('id_Offre', $idOffre)
                ->value('ajouterPar');
            $duree = DB::table('OFFRE')
                ->select('duree')
                ->where('id_Offre', '=', $idOffre)
                ->first();
            $dateDeb = Carbon::parse($request->dateD);
            $dateFin = $dateDeb->copy()->addDays($duree->duree);
            STAGE::where('id_Stage', $request->id)
                ->update(['dateDeb' => $dateDeb, 'dateFin' => $dateFin, 'etatChef' => 'en attente', 'etatResp' => 'en attente', 'motif' => NULL]);
            if ($ajouterPar === 'etudiant') {
                RESPONSABLE::where('id_Resp', $idres)
                    ->update(['nom_Resp' => $request->nomR, 'pre_Resp' => $request->preR, 'email' => $request->email]);
                ENTREPRISE::where('id_Entreprise', $idEnt)
                    ->update(['nom_Entreprise' => $request->nomEnt, 'addr_Entreprise' => $request->Addr, 'telEntreprise' => $request->tel]);
                $currentDateTime = Carbon::now();

                OFFRE::where('id_Offre', $idOffre)
                    ->update([
                        'theme' => $request->theme,
                        'duree' => $request->duree,
                        'contenu' => $request->contenu,
                        'datePost' => $currentDateTime,
                    ]);
            }
            $idEtud = STAGE::where('id_Stage', $request->id)
                ->value('id_Etud');
            $info = DB::table('ETUDIANT')
                ->where('id_Etud', $idEtud)
                ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'ETUDIANT.id_Dep')
                ->join('CHEFDEPARTEMENT', 'CHEFDEPARTEMENT.id_Dep', '=', 'DEPARTEMENT.id_Fac')
                ->select('id_ChefDep', 'nom_Etud', 'pre_Etud')
                ->get();
            $info = json_decode($info, true);

            if ($etatChef != 'en attente') {
                Notification::insert([
                    'destinataire' => 'chefDepartement',
                    'id_Destinataire' => $info[0]['id_ChefDep'],
                    'message' => 'vous avez une demande modifiee par ' .
                    $info[0]['nom_Etud'] . ' ' . $info[0]['pre_Etud'] . '.'
                ]);
            }
            return response()->json([
                'msg' => 'votre demande  ete bien modifier',
            ]);

        } else {
            return response()->json([
                'msg' => 'prdn mt9drch .',
            ]);
        }

    }
    //modifier demande d'un offre qui existe deja

    /*$etatChef = STAGE::where('id_Stage', $request->id)
    ->value('etatChef');
    $etatRes = STAGE::where('id_Stage', $request->id)
    ->value('etatResp');
    if($etatChef !='accepte' || $etatRes==='refuse'){
    STAGE::where('id_Stage', '=',$request->id)
    ->update(['dateDeb' => $request->dateD,
    'dateFin' => $request->dateF,
    ]);
    return response()->json([
    'msg' => 'information updated successfuly']);}
    }  */


    //info demande
    public function infoDemande(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select(
                'theme',
                'duree',
                'datePost',
                'img_Offre',
                'dateDeb',
                'dateFin',
                ' ',
                'nom_Etud',
                'pre_Etud',
                'email',
                'img_Etud',
                'dateNaiss',
                'lieuNaiss',
                'telEtud',
                'numCarte',
                'diplome',
                'specialite'
            )
            ->where('id_Stage', '=', $request->id)
            ->get();
    }
    //consulter etat demande
    public function EtatDemande(Request $request)
    {
        $etatChef = STAGE::where('id_Stage', $request->id)
            ->select(['etatChef'])
            ->get()
            ->value('etatChef');
        $etatRes = STAGE::where('id_Stage', $request->id)
            ->select(['etatResp'])
            ->get()
            ->value('etatResp');

        if ($etatChef === "refuse" || $etatRes === "refuse") {
            return response()->json([
                'msg' => 'refuse',
            ]);
        } elseif ($etatRes === "accepte") {
            return response()->json([
                'msg' => 'accepte',
            ]);
        } else {
            return response()->json([
                'msg' => 'enAttente',
            ]);

        }
    }

    //etat chef , beh 3la hsebo ncriyiw button
    public function EtatChef(Request $request)
    {
        $etatChef = STAGE::where('id_Stage', $request->id)
            ->select(['etatChef'])
            ->get()
            ->value('etatChef');
    }
    //consulter les offres existants
    public function consulterOffre(request $request)
    {
        return DB::table('OFFRE')
            ->join('ENTREPRISE', 'OFFRE.id_ENTREPRISE', '=', 'ENTREPRISE.id_ENTREPRISE')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'Offre.id_Resp')
            ->select(
                'id_Offre',
                'theme',
                'duree',
                'datePost',
                'contenu',
                'img_Offre',
                'nom_Entreprise',
                'telEntreprise',
                'addr_Entreprise',
                'RESPONSABLE.nom_Resp',
                'RESPONSABLE.pre_Resp',
                'showDesc'
            )

            ->where('ajouterPar', '=', 'responsable')
            ->get();
        //return response()->json(['stages' => $stages]);
    }
    //afficher liste des demandes
    public function listeDemande(Request $request)
    {
        $demandes = DB::table('STAGE')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('ENTREPRISE', 'OFFRE.id_ENTREPRISE', '=', 'ENTREPRISE.id_ENTREPRISE')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'Offre.id_Resp')
            ->where('ETUDIANT.id_Etud', '=', $request->id)
            ->select(
                'theme',
                'img_Offre',
                'STAGE.id_Offre',
                'STAGE.id_Stage',
                'duree',
                'contenu',
                'datePost',
                'dateDeb',
                'dateFin',
                'nom_Etud',
                'pre_Etud',
                'ETUDIANT.email as emailEtud',
                'img_Etud',
                'dateNaiss',
                'lieuNaiss',
                'telEtud',
                'numCarte',
                'diplome',
                'specialite',
                'nom_Entreprise',
                'telEntreprise',
                'addr_Entreprise',
                'RESPONSABLE.nom_Resp',
                'RESPONSABLE.pre_Resp',
                'RESPONSABLE.email as emailResp',
                'ajouterPar',
                'etatResp',
                'etatChef',
                'motif',
                'showDetails'
            )
            ->orderby('theme')
            ->get();
        $ListDm = [];
        foreach ($demandes as $demande) {
            $etatChef = STAGE::where('id_Stage', $demande->id_Stage)
                ->select(['etatChef'])
                ->get()
                ->value('etatChef');
            $etatRes = STAGE::where('id_Stage', $demande->id_Stage)
                ->select(['etatResp'])
                ->get()
                ->value('etatResp');
            $ListD = $demande;
            if ($etatChef === "refuse" || $etatRes === "refuse") {
                $ListD->etat = 'refuse';
            } elseif ($etatRes === "accepte") {
                $ListD->etat = 'accepte';
            } else {
                $ListD->etat = 'en attente';

            }
            $ListDm[] = $ListD;
        }
        return response()->json($ListDm);
    }
    //consulter sa presence
    public function consulterPresence(request $request)
    {
        return DB::table('PRESENCE')
            ->where('id_Etud', '=', $request->id)
            ->select('datePresence', 'heureEntr', 'heureSort')
            ->get();
    }
    //consulter ses notes
    public function consulterNotes(request $request)
    {
        return DB::table('NOTATION')
            ->where('NOTATION.id_Etud', '=', $request->id)
            ->join('STAGE', 'STAGE.id_Stage', '=', 'NOTATION.id_Stage')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->select('discipline', 'attitude', 'initiative', 'capacite', 'connaissance', 'noteTotale', 'OFFRE.theme', 'RESPONSABLE.nom_Resp', 'RESPONSABLE.pre_Resp')
            ->get();
    }


    //supprimer une demande (boutton te3 suppression ytaficha 3la hseb etat chef li drnalha fonct lfug )
    public function suppDemande(request $request)
    {

        $ids = DB::table('STAGE')->where('id_Stage', $request->id)
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->select('OFFRE.id_Offre', 'OFFRE.id_Resp', 'ajouterPar')
            ->get();
        $ids = json_decode($ids, true);
        //les offres quui sont ajouter par le responsable ne doivent pas etre supprimes
        if ($ids[0]['ajouterPar'] === 'etudiant') {
            OFFRE::where('id_Offre', '=', $ids[0]['id_Offre'])
                ->delete();
            RESPONSABLE::where('id_Resp', '=', $ids[0]['id_Resp'])
                ->delete();
        }
        STAGE::where('id_Stage', $request->id)
            ->delete();

        return response()->json([
            'msg' => 'demande supprimé',
        ]);

    }


    //voir les notifications
    public function EtudNotif(request $request)
    {
        return Notification::select('message', 'timeStamp', 'etat', 'id_Notification')
            ->where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'etudiant')
            ->orderby('timeStamp', 'DESC')
            ->get();
    }
    // nombre de notification pas vu
    public function unseenStudentNotifNbr(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'etudiant')
            ->where('etat', '=', 'pasVu')
            ->count();
    }
    //nombre de notification deja vu
    public function seeStudentNotif(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'etudiant')
            ->update(['etat' => 'vu']);
    }

    public function uploadCv(request $request)
    {
        $file = $request->file('cv');
        $fileExt = $file->extension();
        $newFileName = 'cv' . $request->id_Etud . '.' . $fileExt;
        // $path = 'pictures/' . $newFileName;
        $request->file('cv')->move(public_path('cvs/'), $newFileName);

        $etudiant = ETUDIANT::where('id_Etud', '=', $request->id_Etud)->first();
        $etudiant->cv = $newFileName;
        $etudiant->save();
    }







}
