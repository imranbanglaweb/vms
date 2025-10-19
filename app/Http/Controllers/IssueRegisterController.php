<?php

namespace App\Http\Controllers;

use App\Models\IssueRegister;
use Illuminate\Http\Request;
use App\Models\Employee;
class IssueRegisterController extends Controller
{
    

   public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $sidebar_menus = Menu::orderBy('menu_oder','ASC')->where('menu_parent',0)->get();
         
        return view('admin.dashboard.dashboard',compact('sidebar_menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $employee_lists = Employee::orderBy('employee_oder','ASC')->get();
         
        return view('admin.dashboard.support.create',compact('employee_lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IssueRegister  $issueRegister
     * @return \Illuminate\Http\Response
     */
    public function show(IssueRegister $issueRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IssueRegister  $issueRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueRegister $issueRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IssueRegister  $issueRegister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueRegister $issueRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IssueRegister  $issueRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueRegister $issueRegister)
    {
        //
    }
}
