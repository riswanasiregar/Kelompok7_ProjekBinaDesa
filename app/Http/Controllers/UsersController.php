<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataUsers'] = User::all();
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all())
        $data['name']     = $request->name;
        $data['email']    = $request->email;
        $data['role']    = $request->role;
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataUsers'] = User::findOrFail($id);
        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $id;
        $users    = User::findOrFail($id);

        $users->name = $request->name;
        $users->email  = $request->email;
        $users->password   = $request->password;


        $users->save();

        return redirect()->route('users.index') ->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::findOrFail($id);

        $users->delete();
        return redirect()->route('users.index')->with('success', 'Data berhasil dihapus');
    }
}
