<?php
  
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DinningController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoomServiceController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Issue;
use App\Http\Controllers\SupportTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IssueregisterLogController;
use App\Http\Controllers\SupportdetailController;
use App\Http\Controllers\AssignprojectDepartmentController;
use App\Http\Controllers\TasktitleController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\LandController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\DocumentHistoryController;
use Illuminate\Support\Facades\Mail;
  
  


Route::group(['middleware' => ['auth']], function() {
Route::redirect('/', 'login');

    // Master Data Management
    // Route::group(['prefix' => 'master', 'as' => 'master.'], function() {
    //     Route::resource('projects', ProjectController::class);
    //     Route::resource('lands', LandController::class);
    //     Route::resource('document-types', DocumentTypeController::class);
    // });

    Route::resource('documents', '\App\Http\Controllers\Admin\DocumentController');

    Route::resource('projects', ProjectController::class);

    Route::get('document-types', [DocumentTypeController::class, 'index'])->name('document-types.index');
    Route::get('document-types/create', [DocumentTypeController::class, 'create'])->name('document-types.create');
    Route::post('document-types', [DocumentTypeController::class, 'store'])->name('document-types.store');
    Route::get('document-types/{id}', [DocumentTypeController::class, 'show'])->name('document-types.show');
    Route::get('document-types/{id}/edit', [DocumentTypeController::class, 'edit'])->name('document-types.edit');
    Route::put('document-types/{id}', [DocumentTypeController::class, 'update'])->name('document-types.update');
    Route::delete('document-types/{id}', [DocumentTypeController::class, 'destroy'])->name('document-types.destroy');

    Route::prefix('document-history')->name('document.history.')->group(function() {
        Route::get('/', [DocumentHistoryController::class, 'index'])->name('index');
        Route::get('/search', [DocumentHistoryController::class, 'search'])->name('search');
        Route::get('/export', [DocumentHistoryController::class, 'export'])->name('export');
    });

    Route::post('documents/{id}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::get('documents/pending-approval', [DocumentController::class, 'pendingApproval'])->name('documents.pending-approval');

    Route::get('documents/{id}/return-modal', [DocumentController::class, 'showReturnModal'])
        ->name('documents.return-modal');
    Route::post('documents/{id}/return', [DocumentController::class, 'returnDocument'])
        ->name('documents.return');

    });
// Route::get('/', [FrontendController::class, 'index'])->name('home_view');

  
  
Route::group(['middleware' => ['prevent-back-history']], function() {

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/contactlistview', [HomeController::class, 'contactlistview'])->name('contactlistview');
Route::any('/contactlistviewdelete/{id}', [FrontendController::class, 'contactlistviewdelete'])->name('contactlistviewdelete');

	Auth::routes();
    Route::resource('roles', RoleController::class);

    Route::resource('supports', SupportController::class);
Route::any('emergency-task', [SupportController::class,'emergencytask'])->name('emergency-task');
Route::any('pendingsupport', [SupportController::class,'pendingsupport'])->name('pendingsupport');
    Route::resource('support-details',SupportdetailController::class);

// export to excel
Route::post('task-entry-export-button', [SupportdetailController::class, 'exportButton'])->name('task-entry.export-button');


Route::get('/support-details/{id}', [SupportdetailController::class, 'show']);
Route::get('/support-details/history/{id}', [SupportdetailController::class, 'history'])->name('support-details.history');

    Route::resource('issueregisterlog',IssueregisterLogController::class);
    Route::resource('support_type', SupportTypeController::class);
    Route::get('user-profile', [UserController::class,'userprofile'])->name('user-profile');
    Route::post('profile-update', [UserController::class,'profileupdate'])->name('profile-update');
    Route::post('profile-password-update', [UserController::class,'profilepasswordupdate'])->name('profile-password-update');
    Route::resource('units', UnitController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('lands', LandController::class);
    // Route::resource('document-types', DocumentTypeController::class);
    Route::resource('employees', EmployeeController::class);

// import
    Route::post('import', [EmployeeController::class, 'import'])->name('employee.import');


// import Upload Task Master Data
    Route::post('import-task', [SupportController::class, 'import'])->name('import-task.import');

    // importuser
    Route::post('importuser', [EmployeeController::class, 'importuser'])->name('employee.importuser');
    // category Import
    Route::post('import-category', [CategoryController::class, 'import'])->name('category.import-category');




Route::post('export', [EmployeeController::class,'export'])->name('employee.export');

// sample_import-file
Route::post('sample_import-file', [SupportController::class,'importexcelfile'])->name('support.sample_import-file');




Route::post('menus/reorder', [MenuController::class, 'menuoder'])->name('menus.reorder');

    Route::resource('permissions', PermissionController::class);
    Route::resource('menus', MenuController::class);
    Route::get('settings', [SettingController::class,'index'])->name('settings');
    Route::post('settings/store', [SettingController::class,'store'])->name('settings.store');

    Route::get('users/search', 'UserController@search')->name('users.search');
    // Route::delete('users/{id}', 'UserController@destroy')->name('users.destroy');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::post('employee/importuser', [UserController::class, 'importUser'])->name('employee.importuser');
    Route::post('employee/export', [UserController::class, 'exportUser'])->name('employee.export');
    // Route::get('users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('users/getData', [UserController::class, 'getData'])->name('users.getData');
    Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
    Route::post('users/validate', [UserController::class, 'validateUser'])->name('users.validate');
    Route::post('users/ajaxSubmit', [App\Http\Controllers\UserController::class, 'ajaxSubmit'])->name('users.ajaxSubmit');

});

Route::get('/test-create', function() {
    $projects = \App\Models\Project::select('id', 'project_name')->orderBy('project_name')->get();
    $lands = \App\Models\Land::select('id', 'name')->orderBy('name')->get();
    $documentTypes = \App\Models\DocumentType::select('id', 'name')->orderBy('name')->get();
    
    return view('admin.dashboard.documents.create_modal', 
        compact('projects', 'lands', 'documentTypes'));
});

Route::resource('users', UserController::class);
Route::post('/documents/export', [DocumentController::class, 'export'])->name('documents.export');

Route::get('documents/{id}/history', [DocumentController::class, 'history'])->name('documents.history');

Route::group(['middleware' => ['auth'], 'prefix' => 'documents'], function () {
    Route::get('/{document}/show', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::post('/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
});

Route::get('/dashboard/recent-documents', [HomeController::class, 'getRecentDocuments'])
    ->name('dashboard.recent-documents');
Route::get('/dashboard/pending-approvals', [HomeController::class, 'getPendingApprovals'])
    ->name('dashboard.pending-approvals');

Route::get('/test-email', function() {
    try {
        \Mail::raw('Test email from Document Register System', function($message) {
            $message->to('md.imran1200@gmail.com')
                   ->subject('Test Email')
                   ->from('md.imran1200@gmail.com', 'Document Register System');
        });
        
        return 'Email sent successfully! Please check your inbox and spam folder.';
    } catch (\Exception $e) {
        \Log::error('Email Error: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/test-approval-email', function() {
    $document = \App\Models\Document::first();
    $user = \App\Models\User::first();
    
    try {
        // Add debug information
        \Log::info('Testing approval email', [
            'document_id' => $document->id,
            'user_email' => $user->email
        ]);
        
        $user->notify(new \App\Notifications\DocumentApproved($document));
        return "Email sent successfully! Check logs for details.";
    } catch (\Exception $e) {
        \Log::error('Test email failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-custom-mail', function() {
    try {
        $document = \App\Models\Document::first();
        $user = \App\Models\User::first();
        
        \Log::info('Testing custom mail template', [
            'user_email' => $user->email,
            'document_id' => $document->id,
            'template' => 'emails.documents.approved'
        ]);
        
        $user->notify(new \App\Notifications\DocumentApproved($document));
        
        return "Custom template mail sent. Check logs and your inbox.";
    } catch (\Exception $e) {
        \Log::error('Custom mail test failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return "Error: " . $e->getMessage();
    }
});


