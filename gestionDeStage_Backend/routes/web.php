<?php
use App\Models\Offre;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\EtudiantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('offerPic/{id}', function ($id) {
    // get the offer image path from the database
    $offer = OFFRE::where('id_Offre', $id)->first();
    $imageName = $offer->img_Offre;
    if ($imageName == null) {
        $path = public_path('pictures/defaultOffre.png');
        return response()->file($path);
    }
    $path = public_path('pictures/' . $imageName);
    if (!file_exists($path)) {
        $path = public_path('pictures/defaultOffre.png');
    }
    return response()->file($path);
});
//Route::get('/getEtudiant', [EtudiantController::class, 'getEtudiant']);
