<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\SessionCoursController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtudiantSpaceController;
use App\Http\Controllers\FormateurSpaceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// --- AUTHENTIFICATION ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ADMINISTRATEUR ---
Route::middleware('auth:web')->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('etudiants/export', [EtudiantController::class, 'exportPdf'])->name('etudiants.export');
    Route::resource('etudiants', EtudiantController::class);
    Route::resource('formateurs', FormateurController::class);
    Route::resource('formations', FormationController::class);
    
    // Inscriptions
    Route::get('inscriptions', [InscriptionController::class, 'index'])->name('inscriptions.index');
    Route::post('inscriptions/{id}/valider', [InscriptionController::class, 'valider'])->name('inscriptions.valider');
    Route::post('inscriptions/{id}/refuser', [InscriptionController::class, 'refuser'])->name('inscriptions.refuser');
    Route::delete('inscriptions/{id}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');
    
    Route::resource('paiements', PaiementController::class);
    Route::resource('sessions', SessionCoursController::class);
});


// --- ETUDIANT ---
Route::middleware('auth:etudiant')->prefix('etudiant')->name('etudiant.')->group(function() {
    Route::get('/dashboard', [EtudiantSpaceController::class, 'dashboard'])->name('dashboard');
    Route::get('/planning', [EtudiantSpaceController::class, 'planning'])->name('planning');
    Route::get('/notes', [EtudiantSpaceController::class, 'notes'])->name('notes');
    Route::get('/notes/export', [EtudiantSpaceController::class, 'exportPdf'])->name('notes.export');
    Route::get('/paiements', [EtudiantSpaceController::class, 'paiements'])->name('paiements');
    
    // Inscriptions
    Route::get('/inscription', [EtudiantSpaceController::class, 'showInscriptionForm'])->name('inscriptions.create');
    Route::post('/inscription', [EtudiantSpaceController::class, 'storeInscription'])->name('inscriptions.store');
});


// --- FORMATEUR ---
Route::middleware('auth:formateur')->prefix('formateur')->name('formateur.')->group(function() {
    Route::get('/dashboard', [FormateurSpaceController::class, 'dashboard'])->name('dashboard');
    Route::get('/etudiants', [FormateurSpaceController::class, 'etudiants'])->name('etudiants');
    Route::get('/etudiants/export', [FormateurSpaceController::class, 'exportEtudiantsPdf'])->name('etudiants.export');
    Route::get('/planning', [FormateurSpaceController::class, 'planning'])->name('planning');
    Route::get('/notes', [FormateurSpaceController::class, 'notes'])->name('notes');
    Route::get('/notes/formation/{formation_id}', [FormateurSpaceController::class, 'showNotesForm'])->name('notes.create');
    Route::post('/notes', [FormateurSpaceController::class, 'storeNote'])->name('notes.store');
});