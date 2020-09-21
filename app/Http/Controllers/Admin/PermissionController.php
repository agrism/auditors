<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index(){
        return view('admin.permissions.index')->with('permissions', Permission::all() );
    }


    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        Permission::create( $request->all() );
        return redirect()->route('admin.permissions.index');
    }

    public function show(Request $request, $id)
    {
        if($request->has('method') && $request->get('method')== 'delete'){
            return $this->destroy($id);
        }
    }

    public function edit($id){
        return view('admin.permissions.edit')->with('permission', Permission::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        Permission::findOrFail($id)->update($request->all());
        return redirect()->route('admin.permissions.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if($permission->roles->count()>0){
            return 'Permission is assigned to '.$permission->roles->count()." role(s), you can't delete this permission!";
        }
        if($permission){
            $permission->delete();
        } else{
            return 'Permission does not exist!';
        }
        $permissions = Permission::get();
        return view('admin.permissions.index', compact('permissions'));
    }
}
