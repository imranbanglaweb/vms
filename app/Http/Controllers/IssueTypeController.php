<?php

namespace App\Http\Controllers;

use App\Models\IssueType;
use Illuminate\Http\Request;

class IssueTypeController extends Controller
{
    


   public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function show(IssueType $issueType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueType $issueType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueType $issueType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IssueType  $issueType
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueType $issueType)
    {
        //
    }
}
