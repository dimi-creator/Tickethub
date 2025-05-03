<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\RegisteredUserController;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'storeContact'])->name('contact.store');

// Routes des événements
Route::get('/evenements', [EventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event}', [EventController::class, 'show'])->name('events.show');
// Route::get('/evenements/creer', [EventController::class, 'create'])->name('events.create');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Profil utilisateur (déjà configuré par Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Achat de billets
    Route::post('/tickets/acheter', [TicketController::class, 'purchase'])->name('tickets.purchase');
    Route::get('/tickets/confirmation', [TicketController::class, 'confirmation'])->name('tickets.confirmation');
    
    // Paiement PayPal
    Route::post('/paiement/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/paiement/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/paiement/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Dashboard utilisateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/tickets', [DashboardController::class, 'tickets'])->name('dashboard.tickets');
    
    // Routes pour organisateurs (middleware spécifique serait mieux)
    Route::get('/dashboard/organisateur', [DashboardController::class, 'organizerDashboard'])->name('dashboard.organizer');
    Route::get('/dashboard/evenements', [DashboardController::class, 'events'])->name('dashboard.events');
    Route::get('/dashboard/statistiques', [DashboardController::class, 'statistics'])->name('dashboard.statistics');

    // Mise à jour des informations d'organisateur
    Route::patch('/organizer', [OrganizerController::class, 'update'])->name('organizer.update');
    
    // Gestion des événements (pour organisateurs)
    Route::get('/events/creer', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}/update', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Devenir organisateur
    Route::get('/devenir-organisateur', [OrganizerController::class, 'create'])->name('organizer.create');
    Route::post('/devenir-organisateur', [OrganizerController::class, 'store'])->name('organizer.store');


    // Routes pour le processus d'achat et paiement
    // Route::middleware('auth')->group(function () {
    // Achat de billets
    Route::post('/tickets/acheter', [TicketController::class, 'purchase'])->name('tickets.purchase');
    Route::get('/tickets/confirmation', [TicketController::class, 'confirmation'])->name('tickets.confirmation');
    
    // Paiement
    Route::get('/paiement', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/paiement/carte', [PaymentController::class, 'processCreditCard'])->name('payment.process.credit-card');
    Route::post('/paiement/paypal', [PaymentController::class, 'processPaypal'])->name('payment.process.paypal');
    Route::get('/paiement', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::post('/paypal/order', [App\Http\Controllers\PaymentController::class, 'createOrder']);



});


    
   

    

