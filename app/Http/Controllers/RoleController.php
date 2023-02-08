<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('view', 'roles');

        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('edit', 'roles');

        $role = Role::create($request->only('name'));

        if($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($role_id)
    {
        Gate::authorize('view', 'roles');

        return new RoleResource(Role::findOrFail($role_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role_id)
    {
        Gate::authorize('edit', 'roles');

        $role = Role::findOrFail($role_id);

        $role->update($request->only('name'));

        DB::table('role_permission')->where('role_id', $role->id)->delete();

        if($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($role_id)
    {
        Gate::authorize('edit', 'users');

        DB::table('role_permission')->where('role_id', $role_id)->delete();
        
        Role::destroy($role_id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
