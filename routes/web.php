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
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LicneseTypeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Issue;
use App\Http\Controllers\SupportTypeController;
use App\Http\Controllers\VehicleController;
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
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\NotificationController;
  


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


    Route::resource('vehicles', VehicleController::class);
Route::resource('vendors', VendorController::class);
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
 // Server-side data endpoints for AJAX tables
    Route::get('units/data', [UnitController::class, 'data'])->name('units.data');
    Route::get('locations/data', [LocationController::class, 'data'])->name('locations.data');
Route::get('departments/data', [DepartmentController::class, 'data'])->name('departments.data');
    Route::get('drivers/data', [DriverController::class, 'data'])->name('drivers.data');



    // Route::get('/', [DriverController::class, 'index'])->name('drivers.index');
    // Route::post('drivers/store', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('drivers/list', [DriverController::class, 'list'])->name('drivers.list');
    Route::resource('drivers', DriverController::class);
    Route::resource('vehicle-type', VehicleTypeController::class);
    Route::resource('requisitions', RequisitionController::class);
    // routes/web.php
// Route::get('/get-employee-details/{id}', [EmployeeController::class, 'getEmployeeDetails'])->name('employee.details');

Route::get('/get-employee-details/{id}', [EmployeeController::class, 'getEmployeeDetails'])->name('employee.details');

Route::get('/requisitions/{id}/download', [RequisitionController::class, 'downloadPDF'])->name('requisitions.download');
 // AJAX search
    Route::get('/requisitions-search', [RequisitionController::class, 'index'])
         ->name('requisitions.search');
Route::post('/requisitions/validate', [RequisitionController::class, 'validateAjax'])
     ->name('requisitions.validate');
Route::get('/requisitions/export-excel', [RequisitionController::class, 'exportExcel'])->name('requisitions.export.excel');
Route::get('/requisitions/export-pdf', [RequisitionController::class, 'exportPDF'])->name('requisitions.export.pdf');
    

// Requisition status update
Route::post('/requisitions/update-status/{id}', 
    [RequisitionController::class, 'updateStatus']
)->name('requisitions.updateStatus');



// Transport Office Approval
Route::post('/requisitions/transport-approve/{id}', 
    [RequisitionController::class, 'transportApprove']
)->name('requisitions.transport.approve');

Route::post('/requisitions/transport-reject/{id}', 
    [RequisitionController::class, 'transportReject']
)->name('requisitions.transport.reject');


// Admin Final Approval
Route::post('/requisitions/admin-approve/{id}', 
    [RequisitionController::class, 'adminApprove']
)->name('requisitions.admin.approve');

Route::post('/requisitions/admin-reject/{id}', 
    [RequisitionController::class, 'adminReject']
)->name('requisitions.admin.reject');




   Route::post('{id}/workflow/update', [RequisitionController::class, 'updateWorkflow'])
        ->name('requisitions.workflow.update');

// Route::post('requisitions/{id}/workflow/update', [RequisitionController::class, 'updateWorkflow'])
//     ->name('requisitions.workflow.update')
//     ->middleware('role:transport,admin'); // only transport and admin can update

Route::group(['middleware' => 'role:employee,transport,admin'], function() {
    Route::resource('requisitions', RequisitionController::class);
});

Route::post('requisitions/{id}/workflow/update', [RequisitionController::class, 'updateWorkflow'])
    ->name('requisitions.workflow.update')
    ->middleware('auth','role:transport,admin'); // only transport & admin




    Route::get('/get-departments-by-unit', [DriverController::class, 'getDepartmentsByUnit'])->name('getDepartmentsByUnit');
Route::get('/get-employee-info', [DriverController::class, 'getEmployeeInfo'])->name('getEmployeeInfo');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/contactlistview', [HomeController::class, 'contactlistview'])->name('contactlistview');
Route::any('/contactlistviewdelete/{id}', [FrontendController::class, 'contactlistviewdelete'])->name('contactlistviewdelete');


// AJAX data endpoint for live refresh
Route::get('/admin/dashboard/data', [HomeController::class, 'data'])->name('admin.dashboard.data');
Route::get('/admin/notifications/unread-count', function(){
    return response()->json(['count' => \App\Models\Notification::where('user_id', auth()->id())->where('is_read', 0)->count()]);
})->name('notifications.unread');

Auth::routes();
Route::resource('roles', RoleController::class);

    Route::resource('supports', SupportController::class);
Route::any('emergency-task', [SupportController::class,'emergencytask'])->name('emergency-task');
Route::any('pendingsupport', [SupportController::class,'pendingsupport'])->name('pendingsupport');
    // Support details routes (guard against missing controller)
    if (class_exists(\App\Http\Controllers\SupportdetailController::class)) {
        Route::resource('support-details', \App\Http\Controllers\SupportdetailController::class);

        // export to excel
        Route::post('task-entry-export-button', [\App\Http\Controllers\SupportdetailController::class, 'exportButton'])->name('task-entry.export-button');

        Route::get('/support-details/{id}', [\App\Http\Controllers\SupportdetailController::class, 'show']);
        Route::get('/support-details/history/{id}', [\App\Http\Controllers\SupportdetailController::class, 'history'])->name('support-details.history');
    }

    Route::resource('issueregisterlog',IssueregisterLogController::class);
    // Support type routes (guarded)
    if (class_exists(\App\Http\Controllers\SupportTypeController::class)) {
        Route::resource('support_type', \App\Http\Controllers\SupportTypeController::class);
    }
    Route::get('user-profile', [UserController::class,'userprofile'])->name('user-profile');
    Route::post('profile-update', [UserController::class,'profileupdate'])->name('profile-update');
    Route::post('profile-password-update', [UserController::class,'profilepasswordupdate'])->name('profile-password-update');
    Route::resource('units', UnitController::class);
   

    // Debug-only endpoint: returns same payload as units.data but only when APP_DEBUG=true.
    // Useful to verify the JSON output without auth redirects during local troubleshooting.
    if (config('app.debug')) {
        Route::get('units/data-debug', [UnitController::class, 'dataDebug'])->name('units.data.debug');
    }
    Route::resource('company', CompanyController::class);
    Route::resource('departments', DepartmentController::class);
    
    // AJAX helpers for unit-wise selects used by employee create/edit forms
    // Note: removed 'departments/unit-wise-company' as it's not used and caused routing conflicts
    Route::get('unit-wise-department', [DepartmentController::class, 'unitWiseDepartment'])->name('unit-wise-department');
    Route::resource('locations', LocationController::class);
    
    Route::resource('projects', ProjectController::class);
    Route::resource('lands', LandController::class);
    // Route::resource('document-types', DocumentTypeController::class);
    Route::resource('employees', EmployeeController::class);


    Route::resource('license-types', LicneseTypeController::class);

    // Categories resource (some views expect routes like categories.index)
    if (class_exists(\App\Http\Controllers\CategoryController::class)) {
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    }

// import
    Route::post('import', [EmployeeController::class, 'import'])->name('employee.import');


// import Upload Task Master Data
    Route::post('import-task', [SupportController::class, 'import'])->name('import-task.import');

    // importuser
    Route::post('importuser', [EmployeeController::class, 'importuser'])->name('employee.importuser');
    // category Import
    Route::post('import-category', [CategoryController::class, 'import'])->name('category.import-category');


Route::post('export', [EmployeeController::class,'export'])->name('employee.export');
Route::post('export', [RequisitionController::class,'exportExcel'])->name('requisitions.export');
// sample_import-file
Route::post('sample_import-file', [SupportController::class,'importexcelfile'])->name('support.sample_import-file');


Route::get('notifications', [NotificationController::class, 'index'])
    ->name('admin.notifications.all');


Route::post('menus/reorder', [MenuController::class, 'menuoder'])->name('menus.reorder');

    Route::resource('permissions', PermissionController::class);
    Route::resource('menus', MenuController::class);
    Route::get('settings', [SettingController::class,'index'])->name('settings');
    Route::post('settings/store', [SettingController::class,'store'])->name('settings.store');

    Route::get('users/search', [\App\Http\Controllers\UserController::class, 'search'])->name('users.search');
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



