<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\ChefDepController;
use App\Http\Controllers\LoginController;
use App\Models\Certificate;
use App\Models\Etudiant;
use App\Models\Stage;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::get('/certificate', function (Request $request) {
    $certificate = Certificate::where('token', $request->token)->first();
    if ($certificate) {
        $etudiant = ETUDIANT::where('id_Etud', $certificate->id_Etud)->first();
        $stage = STAGE::where('id_Stage', $certificate->id_Stage)->first();

        return response()->json([
            'message' => ' Attestation réelle',
            'etudiant' => $etudiant,
            'stage' => $stage,
        ], 200);
    } else {
        return response()->json([
            'message' => 'Certificat introuvable',
        ], 200);
    }
});
//fonctions etudiant 17
Route::post('/creeCompte', [EtudiantController::class, 'creeCompte']);
Route::post('/InfoEtud', [EtudiantController::class, 'InfoEtud']);
Route::post('/updateInfoEtud', [EtudiantController::class, 'updateInfoEtud']);
Route::post('/postulerOffre', [EtudiantController::class, 'postulerOffre']);
Route::post('/creerDemande', [EtudiantController::class, 'creerDemande']);
Route::post('/modifierDemande', [EtudiantController::class, 'modifierDemande']);
Route::post('/EtatDemande', [EtudiantController::class, 'EtatDemande']);
Route::post('/infoDemande', [EtudiantController::class, 'infoDemande']);
Route::post('/listeDemande', [EtudiantController::class, 'listeDemande']);
Route::delete('/suppDemande', [EtudiantController::class, 'suppDemande']);
Route::post('/consulterPresence', [EtudiantController::class, 'consulterPresence']);
Route::post('/consulterNotes', [EtudiantController::class, 'consulterNotes']);
Route::get('/consulterOffre', [EtudiantController::class, 'consulterOffre']);
Route::post('/EtudNotif', [EtudiantController::class, 'EtudNotif']);
Route::post('/unseenStudentNotifNbr', [EtudiantController::class, 'unseenStudentNotifNbr']);
Route::post('/seeStudentNotif', [EtudiantController::class, 'seeStudentNotif']);
Route::post('/EtatChef', [EtudiantController::class, 'EtatChef']);
Route::post('/uploadCv', [EtudiantController::class, 'uploadCv']);

//fonct chef dep 24
Route::get('/NotesEtud', [ChefDepController::class, 'NotesEtud']);
Route::get('/consulterListOffre', [ChefDepController::class, 'consulterListOffre']);
Route::get('/listDemandeAttente', [ChefDepController::class, 'listDemandeAttente']);
Route::get('/listDemandeAccepte', [ChefDepController::class, 'listDemandeAccepte']);
Route::get('/listDemandeRefuse', [ChefDepController::class, 'listDemandeRefuse']);
Route::post('/creeCompteResp', [ChefDepController::class, 'creeCompteResp']);
Route::post('/ChefAccepter', [ChefDepController::class, 'ChefAccepter']);
Route::get('/listResp', [ChefDepController::class, 'listResp']);
Route::post('/InfoResp', [ChefDepController::class, 'InfoResp']);
Route::get('/listEtud', [ChefDepController::class, 'listEtud']);
Route::get('/listStagiaire', [ChefDepController::class, 'listStagiaire']);
Route::post('/InfoEtuds', [ChefDepController::class, 'InfoEtuds']);
Route::post('/changeInfoEtud', [ChefDepController::class, 'changeInfoEtud']);
Route::post('/changeInfoResp', [ChefDepController::class, 'changeInfoResp']);
Route::delete('/suppEtud', [ChefDepController::class, 'suppEtud']);
Route::delete('/suppResp', [ChefDepController::class, 'suppResp']);
Route::post('/chefNotif', [ChefDepController::class, 'chefNotif']);
Route::post('/unseenChefNotifNbr', [ChefDepController::class, 'unseenChefNotifNbr']);
Route::post('/seeChefNotif', [ChefDepController::class, 'seeChefNotif']);
Route::get('/InfoChef', [ChefDepController::class, 'InfoChef']);
Route::post('/changeInfoChef', [ChefDepController::class, 'changeInfoChef']);
Route::post('/ChefAccepter', [ChefDepController::class, 'ChefAccepter']);
Route::post('/confirmCreation', [ChefDepController::class, 'confirmCreation']);
Route::post('/refuserDemande', [ChefDepController::class, 'refuserDemande']);
Route::post('/envoyerMotif', [ChefDepController::class, 'envoyerMotif']);

//responable 16

Route::post('/listDemandeAttenteR', [ResponsableController::class, 'listDemandeAttenteR']);
Route::post('/listDemandeAccepteR', [ResponsableController::class, 'listDemandeAccepteR']);
Route::post('/listDemandeRefuseR', [ResponsableController::class, 'listDemandeRefuseR']);
Route::post('/listStagiaires', [ResponsableController::class, 'listStagiaires']);
Route::post('/listStagiairesPasNoter', [ResponsableController::class, 'listStagiairesPasNoter']);
Route::post('/listStagiairesNote', [ResponsableController::class, 'listStagiairesNote']);
Route::post('/sesOffre', [ResponsableController::class, 'sesOffre']);
Route::post('/InfoRespR', [ResponsableController::class, 'InfoRespR']);
Route::post('/changeInfoRespR', [ResponsableController::class, 'changeInfoRespR']);
Route::post('/accepterDemandeR', [ResponsableController::class, 'accepterDemandeR']);
Route::post('/refuserDemandeR', [ResponsableController::class, 'refuserDemandeR']);
Route::post('/envoyerMotifR', [ResponsableController::class, 'envoyerMotifR']);
Route::post('/noterEtud', [ResponsableController::class, 'noterEtud']);
Route::post('/presence', [ResponsableController::class, 'presence']);
Route::post('/listPresence', [ResponsableController::class, 'listPresence']);
Route::post('/listTousPresence', [ResponsableController::class, 'listTousPresence']);
Route::post('/ajouterOffre', [ResponsableController::class, 'ajouterOffre']);
Route::post('/respNotif', [ResponsableController::class, 'respNotif']);
Route::post('/unseenRespNotifNbr', [ResponsableController::class, 'unseenRespNotifNbr']);
Route::post('/seeRespNotif', [ResponsableController::class, 'seeRespNotif']);
Route::delete('/suppOffre', [ResponsableController::class, 'suppOffre']);
Route::post('/modifierOffre', [ResponsableController::class, 'modifierOffre']);
Route::post('/genererPdf', [ResponsableController::class, 'genererPdf']);
Route::post('/suppNotif', [ResponsableController::class, 'suppNotif']);
Route::get('/downloadCV', [ResponsableController::class, 'downloadCV']);


//authentification
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
