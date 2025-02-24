<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Ville;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\EleveTenantController;
use App\Http\Controllers\ClasseOptionController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProfesseurTenantController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        //dd(User::limit(1)->get());
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
    Route::get('/login', [AuthenticationController::class, 'show'])->name('login');

    Route::get('/logout',[AuthenticationController::class,'logout'])->name('logout');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticateTenant']);


    // Route::middleware(['auth','redirect_role'])->group(function(){

    // });
    
    Route::middleware(['auth', 'checkRole:App\Models\Administrateur'])->group(function () {
    //Prof tenant.php

        Route::get('/dashboard_admin', [AuthenticationController::class, 'dashboardAdmin'])->name('dashboard_admin');
        Route::get('/listeProfAdmin', [ProfesseurController::class, 'listeProfesseurs'])->name('listeProfAdmin');
        Route::get('/ajoutProfAdmin', [ProfesseurController::class, 'ajoutProfesseur'])->name('ajoutProfAdmin');
        Route::post('/enregistrer_prof', [ProfesseurController::class, 'enregistrementProfesseur']);
        Route::get('/supprimerProfesseur/{id}',[ProfesseurController::class,'supprimerProfesseur'])->name('supprimerProfesseur')->middleware('auth');
        
    //Matiere tenant.php
  //Matiere tenant.php

  Route::get('/listeMatiereAdmin', [ProfesseurController::class, 'listeMatiere'])->name('listeMatiereAdmin');
  Route::get('/ajoutMatiereAdmin', [ProfesseurController::class, 'ajoutMatiere'])->name('ajoutMatiereAdmin');
  Route::post('/enregistrementMatiere', [ProfesseurController::class, 'enregistrementMatiere']);
  Route::get('/supprimerMatiere/{id}',[ProfesseurController::class,'supprimerMatiere'])->name('supprimerMatiere')->middleware('auth');
  Route::get('/ficheProfesseur/{id}', [ProfesseurController::class, 'ficheProfesseur'])->name('ficheProfesseur');
  Route::post('/associationProfesseurMatiere', [ProfesseurController::class, 'associationProfesseurMatiere'])->name('associationProfesseurMatiere');
  Route::post('/desassociationProfesseurMatiere', [ProfesseurController::class, 'desassociationProfesseurMatiere'])->name('desassociationProfesseurMatiere');
  Route::get('/matiere/fiche/{matiereId}', [ProfesseurController::class, 'ficheMatiere'])->name('fiche.matiere');
       

    //Classe tenant.php

        Route::get('/listeClasseAdmin', [ClasseController::class, 'listeClasses'])->name('listeClasseAdmin')->middleware('auth');
        Route::get('/ajoutClasseAdmin', [ClasseController::class, 'ajoutClasse'])->name('ajoutClasseAdmin');
        Route::get('/supprimer_classe/{id}', [ClasseController::class, 'supprimerClasse'])->name('suppression_classe')->middleware('auth');
        Route::post('/enregistrer_classe', [ClasseController::class, 'enregistrementClasse']);
        Route::get('/info_classe/{id}', [ClasseController::class, 'infoClasse'])->name('infoClasseAdmin')->where('id', '[0-9]+')->middleware('auth');
        Route::post('/assigner_professeur', [ClasseController::class, 'assignerProfesseur'])->name('assigner_professeur');
        Route::post('/assignerProfesseurDelete', [ClasseController::class, 'assignerProfesseurDelete'])->name('assignerProfesseurDelete');
        
        Route::get('dropdown', [AdminController::class, 'index']);
        Route::post('api/fetch-states', [AdminController::class, 'fetchState']);
        Route::post('api/fetch-cities', [AdminController::class, 'fetchCity']);
        Route::get('/retirer_classe/{id}', [AdminController::class, 'retirerClasse'])->name('retirer_classe')->middleware('auth');


            


        Route::get('/associer_classe_filiere', [AdminController::class, 'associerClasseFiliere'])->name('associerClasseFiliere');;

        
        //Eleve tenant.php      
        Route::get('/listeEleveAdmin', [AdminController::class, 'listeEleves'])->name('listeEleveAdmin');
        Route::get('/ajoutEleveAdmin', [AdminController::class, 'ajoutEleve'])->name('ajoutEleveAdmin');
        Route::post('/enregistrer_eleve', [AdminController::class, 'enregistrementEleve']);
        Route::post('/supprimer_eleve/{id}', [AdminController::class, 'suppressionEleve'])->name('supprimer_eleve')->middleware('auth');
        Route::post('/attribuer_classe/{id}', [AdminController::class, 'attribuerClasse'])->name('attribuer_classe');
        Route::post('/fiche_eleve/{id}', [AdminController::class, 'ficheEleve'])->name('fiche_eleve')->middleware('auth');
        //filiere tenant.php

        Route::get('/dashboardPrincipal', [FiliereController::class, 'index'])->name('dashboard.principal');
        Route::get('/ListeFiliereAdmin', [FiliereController::class, 'listeFiliere'])->name('ListeFiliereAdmin');
        Route::get('/AjoutFiliereAdmin',[FiliereController::class,'ajouterFiliere'])->name('AjoutFiliereAdmin')->middleware('auth');
        Route::post('/enregistrer_filiere', [FiliereController::class, 'enregistrementFiliere'])->middleware('auth');
        Route::get('/supprimmer_filiere/{id}', [FiliereController::class, 'suppressionFiliere'])->name('suppression_filiere');
        Route::get('/filiere/{filiere_id}', [FiliereController::class, 'VueFiliere'])->name('filiere.associer');
        

        Route::post('/filiere/associer-matieres/{filiere_id}', [FiliereController::class, 'associerMatiere'])->name('associerMatiere');
        Route::post('/filiere/{id}/supprimer-matiere', [FiliereController::class, 'supprimerMatiere'])->name('supprimerMatiere');

        Route::get('/filiere/{id}/classes', [FiliereController::class, 'afficherClasseFiliere'])->name('afficherClasse');

        // Classe Option

        Route::get('/listeClasseOptionAdmin', [ClasseOptionController::class, 'listeClasseOption'])->name('listeClasseOptionAdmin')->middleware('auth');
       Route::get('/ajoutClasseOptionAdmin', [ClasseOptionController::class, 'ajoutClasseOption'])->name('ajoutClasseOptionAdmin');
       Route::post('/enregistrer_classe_option', [ClasseOptionController::class, 'enregistrementClasseOption']);

        Route::post('/assigner_professeur_option', [ClasseOptionController::class, 'assignerProfesseurOption'])->name('assigner_professeur_option');
        Route::get('/info_classe_option/{id}', [ClasseOptionController::class, 'infoClasseOption'])->name('infoClasseOptionAdmin')->middleware('auth');
        Route::get('/supprimerClasseOption/{id}',[ClasseOptionController::class,'supprimerClasseOption'])->name('supprimerClasseOption')->middleware('auth');
        Route::post('/attribuer_classeOption/{id}', [ClasseOptionController::class, 'attribuerClasseOption'])->name('attribuer_classeOption');
        Route::get('/retirer_classeOption/{id}', [ClasseOptionController::class, 'retirerClasseOption'])->name('retirer_classeOption');
    });

    Route::middleware(['auth', 'checkRole:App\Models\Professeur'])->group(function () {
        Route::get('/dashboard_professeur', [AuthenticationController::class, 'dashboardProfesseurs'])->name('dashboard_professeur');
        Route::get('/listeClassesProf', [ProfesseurTenantController::class, 'listeClassesProf'])->name('listeClassesProf');
        Route::get('/listeElevesProf/{id}', [ProfesseurTenantController::class, 'listeElevesProf'])->name('listeElevesProf');
        Route::get('/creation_examen', [ProfesseurTenantController::class, 'mesmatieres'])->name('creationExamen');
        Route::get('/get-filieres/{matiereId}', [ProfesseurController::class, 'getFilieres'])->name('get.filieres');
        Route::get('/associer_examen', [ProfesseurTenantController::class, 'associerExamen'])->name('associer_examen');

        Route::post('/enregistrement_examen', [ProfesseurTenantController::class, 'enregistrementExamen'])->name('enregistrement_examen');
        Route::post('/associer_examen', [ProfesseurTenantController::class, 'associerExamen'])->name('associer_examen');
        Route::post('/desassociationEleveExamen', [ProfesseurTenantController::class, 'desassociationEleveExamen'])->name('desassociationEleveExamen');
        Route::get('/PageNote/{id}', [ProfesseurTenantController::class, 'PageNote'])->name('PageNote');
        Route::post('/Note/{id}', [ProfesseurTenantController::class, 'Note'])->name('Note');
        Route::get('/liste_examens', [ProfesseurTenantController::class, 'listeExamens'])->name('liste_examens');
        Route::get('/FicheExamen/{id}', [ProfesseurTenantController::class, 'FicheExamen'])->name('FicheExamen');
        Route::get('/supprimerExamen/{id}', [ProfesseurTenantController::class, 'supprimerExamen'])->name('supprimerExamen');     
        Route::post('/retirerExamenEleve', [ProfesseurTenantController::class, 'retirerExamenEleve'])->name('retirer_examen_eleve');
        Route::get('/download/examen/{filename}', [ProfesseurTenantController::class, 'download'])->name('download.examen');
        Route::get('/infoEleveProfesseur/{id}', [ProfesseurTenantController::class, 'infoEleveProfesseur'])->name('infoEleveProfesseur');
        
        
    });

    Route::middleware(['auth', 'checkRole:App\Models\Eleve'])->group(function () {
        Route::get('/dashboard_eleve', [AuthenticationController::class, 'dashboardEleve'])->name('dashboard_eleve');
        Route::get('accueil', [EleveTenantController::class, 'index'])->name('dashboard.principal');
        Route::get('/mes_cours', [EleveTenantController::class, 'listeProfSelonEleve'])->name('eleve.professeurs');
        Route::get('/voir_mes_notes', [EleveTenantController::class, 'voirMesNotes'])->name('eleve.notes');


    });
    
    

});
