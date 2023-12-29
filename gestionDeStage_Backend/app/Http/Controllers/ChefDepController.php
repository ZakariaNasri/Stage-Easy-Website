<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
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
use App\Models\Universite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ChefDepController extends Controller
{
    //consulter notes
    public function NotesEtud(request $request)
    {
        return DB::table('NOTATION')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'NOTATION.id_Etud')
            ->select('noteTotale', 'nom_Etud', 'pre_Etud', 'email')
            ->get();

    }

    //consulter list des offres
    public function consulterListOffre(request $request)
    {
        return DB::table('OFFRE')
            ->join('ENTREPRISE', 'OFFRE.id_ENTREPRISE', '=', 'ENTREPRISE.id_ENTREPRISE')
            ->join('RESPONSABLE', 'RESPONSABLE.id_Resp', '=', 'OFFRE.id_Resp')
            ->select(
                'theme',
                'duree',
                'id_Offre',
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
    }
    //consulter list des demandes en qttente
    public function listDemandeAttente(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('theme', 'img_Offre', 'nom_Etud', 'pre_Etud', 'email', 'id_Stage','STAGE.id_Offre')
            ->where('etatChef', '=', 'en attente')
            ->get();
    }


    //consulter list des demandes acceptees
    public function listDemandeAccepte(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('theme', 'img_Offre', 'nom_Etud', 'pre_Etud', 'email','STAGE.id_Offre')
            ->where('etatChef', '=', 'accepte')
            ->get();
    }
    //consulter list des demandes refuse
    public function listDemandeRefuse(Request $request)
    {
        return DB::table('OFFRE')
            ->join('STAGE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('nom_Etud', 'theme', 'img_Offre', 'pre_Etud', 'email','STAGE.id_Offre')
            ->where('etatChef', '=', 'refuse')
            ->get();
    }
    //consulter ses info
    public function InfoChef(request $request)
    {
        return DB::table('CHEFDEPARTEMENT')
            ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'CHEFDEPARTEMENT.id_Dep')
            ->join('FACULTE', 'FACULTE.id_Fac', '=', 'DEPARTEMENT.id_Fac')
            ->join('UNIVERSITE', 'FACULTE.id_Univ', '=', 'UNIVERSITE.id_Univ')
            ->select('nom_ChefDep', 'pre_ChefDep', 'email', 'img_ChefDep', 'nom_Dep', 'nom_Fac', 'nom_Univ')
            ->where('id_ChefDep', '=', $request->id)
            ->get();
    }

    //modifier ses info
    public function changeInfoChef(request $request)
    {

        $ChefD = DB::table('CHEFDEPARTEMENT')->where('id_ChefDep', $request->id)->get();
        $ChefD = json_decode($ChefD, true);
        if (Hash::check($request->currentMdps, $ChefD[0]['password'])) {
            DB::table('CHEFDEPARTEMENT')
                ->where('id_ChefDep', '=', $request->id)
                ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'CHEFDEPARTEMENT.id_Dep')
                ->join('FACULTE', 'FACULTE.id_Fac', '=', 'DEPARTEMENT.id_Fac')
                ->join('UNIVERSITE', 'FACULTE.id_Univ', '=', 'UNIVERSITE.id_Univ')
                ->update([
                    'nom_ChefDep' => $request->nom,
                    'pre_ChefDep' => $request->prenom,
                    'email' => $request->email,
                ]);
            if ($request->nvMdps != "") {
                // The new password is valid
                CHEFDEP::where('id_ChefDep', $request->id)
                    ->update(['password' => Hash::make($request->nvMdps)]);
            }
            return response()->json(['msg' => 'informations updated successfully']);
        } else
            return response()->json([
                'msg' => 'wrong password',
            ]);
    }



    //nb3tulu teni email fih mdps t3o

    public function creeCompteResp(request $request)
    {
        // verififier si le compte du responsable existe deja
        $responsableExist = RESPONSABLE::where('email', $request->email)->exists();
        if ($responsableExist) {
            return response()->json(['error' => 'Un compte avec cet e-mail existe déjà.'], 400);
        }
        //verifier si l'entreprise exite deja dans la base
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
        $password = Str::random(8);
        DB::table('RESPONSABLE')
            ->insert([
                'nom_Resp' => $request->nom,
                'pre_Resp' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($password),
                'img_Resp' => $request->img,
                'id_Entreprise' => $entrepriseId
            ]);

        Mail::to($request->email)->send(new Email($password));
        return response()->json(['msg' => 'Le compte a été créé']);
    }


    //list responsables
    public function listResp(request $request)
    {
        return DB::table('RESPONSABLE')
            ->select('nom_Resp', 'pre_Resp', 'email', 'id_Resp')
            ->where('compteExist', '=', 'Exist')
            ->orderby('nom_Resp', 'asc')
            ->get();
    }
    //list etudiant
    public function listEtud(request $request)
    {
        return DB::table('ETUDIANT')
            ->select('nom_Etud', 'pre_Etud', 'numCarte', 'email', 'specialite', 'id_Etud')
            ->orderby('nom_Etud', 'asc')
            ->get();
    }
    public function listStagiaire(request $request)
    {

        return DB::table('STAGE')
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('NOTATION', 'NOTATION.id_Etud', '=', 'STAGE.id_Etud')
            ->select('nom_Etud', 'pre_Etud', 'email', 'specialite', 'noteTotale')
            ->where('etatResp', '=', 'accepte')
            ->orderby('nom_Etud', 'asc')
            ->get();
    }

    // lzm 3lih yselectioni se3 resp wmb3d naffichiw wela nafichiw klch
//y3ni show more hdik tstha9 fonc brk whda kima hdi li drnaha wela lzmlha 2 fonct
// ki ndiro select* hdi fl front whdo ydetecte bli mdps maytafichach wela lzm nselectioniw wch habin nafichiw

    public function InfoResp(request $request)
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
            )
            ->where('RESPONSABLE.id_Resp', '=', $request->id)
            ->get();
    }


    //les information d'un etudiant
    public function InfoEtuds(request $request)
    {
        return DB::table('ETUDIANT')
            ->join('DEPARTEMENT', 'DEPARTEMENT.id_Dep', '=', 'ETUDIANT.id_Dep')
            ->join('FACULTE', 'FACULTE.id_Fac', '=', 'DEPARTEMENT.id_Fac')
            ->join('UNIVERSITE', 'FACULTE.id_Univ', '=', 'UNIVERSITE.id_Univ')
            ->select(
                'nom_Etud',
                'pre_Etud',
                'email',
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

    //fonct modifier les info etudiant
    public function changeInfoEtud(request $request)
    {

        Etudiant::where('id_Etud', $request->id)
            ->update([
                'nom_Etud' => $request->nom,
                'pre_Etud' => $request->prenom,
                'email' => $request->email,
                'img_Etud' => $request->img,
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
                ->update(['password' => $request->nvMdps]);
        }


        return response()->json(['msg' => 'information updated successfully',]);
    }

    // fonct modifier les info resp
    public function changeInfoResp(request $request)
    {
        $ENTREPRISE = DB::table('RESPONSABLE')
            ->where('id_Resp', '=', $request->id)
            ->get()
            ->value("id_Entreprise");

        DB::table('ENTREPRISE')
            ->where('id_Entreprise', '=', $ENTREPRISE)
            ->update([
                'nom_Entreprise' => $request->nomEnt,
                'telEntreprise' => $request->telEnt,
                'addr_Entreprise' => $request->addrEnt
            ]);

        RESPONSABLE::where('id_Resp', '=', $request->id)
            ->update([
                'nom_Resp' => $request->nom,
                'pre_Resp' => $request->prenom,
                'email' => $request->email,
                'img_Resp' => $request->img,
                "id_Entreprise" => $ENTREPRISE
            ]);


        if ($request->nvMdps != "" && strlen($request->nvMdps) >= 6) {
            // The new password is valid
            RESPONSABLE::where('id_Resp', $request->id)
                ->update(['password' => $request->nvMdps]);
        }


        return response()->json(['msg' => 'information updated successfully',]);

    }
    //supp compte etudiant
    public function suppEtud(request $request)
    {

        ETUDIANT::where('id_Etud', $request->id)
            ->delete();

        return response()->json([
            'msg' => 'compte supprimé',
        ]);

    }
    //supp compte resp
    public function suppResp(request $request)
    {
        $ENTREPRISE = DB::table('RESPONSABLE')
            ->where('id_Resp', '=', $request->id)
            ->get()
            ->value("id_Entreprise");

        DB::table('ENTREPRISE')
            ->where('id_Entreprise', '=', $ENTREPRISE)
            ->delete();

        RESPONSABLE::where('id_Resp', '=', $request->id)
            ->delete();

        return response()->json([
            'msg' => 'compte supprimé',
        ]);

    }
    //voir les notifications
    public function chefNotif(request $request)
    {
        return Notification::select('message', 'timeStamp', 'id_Notification')
            ->where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'chefDepartement')
            ->orderby('timeStamp', 'DESC')
            ->get();
    }
    // nombre de notification pas vu
    public function unseenChefNotifNbr(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'chefDepartement')
            ->where('etat', '=', 'pasVu')
            ->count();
    }

    //nombre de notification deja vu
    public function seeChefNotif(request $request)
    {
        return Notification::where('id_Destinataire', '=', $request->id)
            ->where('destinataire', '=', 'chefDepartement')
            ->update(['etat' => 'vu']);
    }

    //accepter demande
    public function ChefAccepter(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['etatChef' => 'accepte', 'etatResp' => 'en attente']);
        response()->json(['msg' => 'information updated successfully',]);

        $fullName = STAGE::where('id_Stage', '=', $request->id)
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('nom_Etud', 'pre_Etud')
            ->get();
        $fullName = json_decode($fullName, true);



        $id = STAGE::where('id_Stage', '=', $request->id)
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->select('OFFRE.id_Offre', 'OFFRE.id_Resp', 'OFFRE.ajouterPar')
            ->get();

        $id = json_decode($id, true);

        /* $idRes=DB::table('STAGE')
         ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
         ->where('id_Stage', '=',$request->id)
         ->value('OFFRE.id_Resp');*/

        $email = DB::table('RESPONSABLE')
            ->where('id_Resp', '=', $id[0]['id_Resp'])
            ->value('email');

        $ResExist = RESPONSABLE::where('email', $email)
            ->where('compteExist', '=', 'Exist')
            ->first();

        if ($ResExist) {
            $idResp = $ResExist->id_Resp;
            $idEntr = $ResExist->id_Entreprise;

            OFFRE::where('OFFRE.id_Offre', '=', $id[0]['id_Offre'])
                ->update(['id_Resp' => $idResp, 'id_Entreprise' => $idEntr]);

            if ($id[0]['ajouterPar'] == 'etudiant') {
                DB::table('RESPONSABLE')
                    ->where('id_Resp', '=', $id[0]['id_Resp'])
                    ->delete();
            }



            $queryState = Notification::insert(['destinataire' => 'responsable', 'id_Destinataire' => $idResp, 'message' => 'You have a new request from ' . $fullName[0]['nom_Etud'] . ' ' . $fullName[0]['pre_Etud'] . '.']);
            if (!$queryState) {
                return response()->json([
                    'msg' => 'operation failed (inserting notification)',
                ]);
            }
            return response()->json(['msg' => 'La demande a ete acceptee',]);
        } else {
            return response()->json(['msg' => 'confirmer la creation du compte',]);
        }

    }

    public function confirmCreation(request $request)
    {
        $password = Str::random(8);
        $idOffre = DB::table('STAGE')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->where('id_Stage', '=', $request->id)
            ->value('OFFRE.id_Offre');
        $idRes = DB::table('OFFRE')
            ->where('id_Offre', '=', $idOffre)
            ->value('id_Resp');

        $email = DB::table('RESPONSABLE')
            ->where('id_Resp', '=', $idRes)
            ->value('email');


        RESPONSABLE::where('id_Resp', '=', $idRes)
            ->update(['password' => Hash::make($password), 'compteExist' => 'exist']);
        Mail::to($email)->send(new Email($password));

        $fullName = STAGE::where('id_Stage', '=', $request->id)
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->select('nom_Etud', 'pre_Etud')
            ->get();
        $fullName = json_decode($fullName, true);
        Notification::insert([
            'destinataire' => 'responsable',
            'id_Destinataire' => $idRes,
            'message' => 'vous avez une nouvelle demande  ' . $fullName[0]['nom_Etud'] . ' ' . $fullName[0]['pre_Etud'] . '.'
        ]);
        return response()->json(['msg' => 'le compte a ete cree'],200);

    }

    //refuser une demande

    public function refuserDemande(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['etatChef' => 'refuse']);

        $id = STAGE::where('id_Stage', '=', $request->id)
            ->join('ETUDIANT', 'ETUDIANT.id_Etud', '=', 'STAGE.id_Etud')
            ->join('OFFRE', 'OFFRE.id_Offre', '=', 'STAGE.id_Offre')
            ->select('ETUDIANT.id_Etud', 'theme')
            ->get();
        $id = json_decode($id, true);

        Notification::insert([
            'destinataire' => 'etudiant',
            'id_Destinataire' => $id[0]['id_Etud'],
            'message' => 'votre demande ' . $id[0]['theme'] . ' a ete refusee par le chef de departement, vous pouvez consulter le motif pour bien comprendre '
        ]);

        return response()->json(['msg' => 'la demande a ete refuse']);

    }

    // envoyer un motif
    public function envoyerMotif(request $request)
    {
        STAGE::where('id_Stage', '=', $request->id)
            ->update(['motif' => $request->motif]);
        return response()->json(['msg' => 'voici le motif ']);

    }

}
