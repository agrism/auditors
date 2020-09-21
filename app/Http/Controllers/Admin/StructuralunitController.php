<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Structuralunit;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StructuralunitController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($companyId)
    {
        $structuralunits = Structuralunit::where('company_id', $companyId)->get();
        $company = Company::where('id', $companyId)->first();

        return view('admin.companies.structuralunits.index', compact('company', 'structuralunits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($companyId)
    {
        $company = Company::where('id', $companyId)->first();

        return view('admin.companies.structuralunits.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($companyId, Request $request)
    {

        $data = $request->all();
        $data['company_id'] = $companyId;
        Structuralunit::create($data);

        return redirect()->route('admin.company.structuralunits.index', $companyId);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($companyIs, $id, Request $request)
    {
        if($request->has('method') && $request->get('method')== 'delete'){
            return $this->destroy($comopanyId, $id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($companyId, $id)
    {
        $structuralunit = Structuralunit::where('company_id', $companyId)->find($id);
        $company = Company::find($companyId);

        return view('admin.companies.structuralunits.edit', compact('structuralunit', 'company'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $id)
    {
        $structuralunit = Structuralunit::where('company_id', $companyId)->find($id);
        $data = $request->all();
        $structuralunit->update($data);

        return redirect()->route('admin.company.structuralunits.index', $companyId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($companyId, $id)
    {
        $structuralunit = Structuralunit::with('invoices')->where('company_id', $companyId)->find($id);
        if($structuralunit){
            if($structuralunit->invoices()->count() >0){
                return 'Structural unit is attachad to one or more invocises, therefpre you cant delete it!';
            }
            $structuralunit->delete();
        } else{
            return 'structuralunit do not exists!';
        }
        return redirect()->route('admin.company.structuralunits.index', $companyId);
    }
}
