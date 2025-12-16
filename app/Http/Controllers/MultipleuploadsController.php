<?php

namespace App\Http\Controllers;

use App\Models\Multipleuploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MultipleuploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $uploads = Multipleuploads::where('user_id', Auth::id())->latest()->get();

        return view('multipleuploads', compact('uploads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'filename' => 'required|array',
            'filename.*' => 'mimes:doc,docx,pdf,jpg,jpeg,png|max:2048'
        ]);

        if (!$request->hasFile('filename')) {
            return back()->withErrors(['filename' => 'File tidak ditemukan.']);
        }

        foreach ($request->file('filename') as $file) {
            $storedPath = $file->store('multiple-uploads', 'public');

            Multipleuploads::create([
                'filename' => $storedPath,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('uploads')->with('success', 'File berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Multipleuploads $multipleuploads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Multipleuploads $multipleuploads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Multipleuploads $multipleuploads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Multipleuploads $multipleupload)
    {
        if ($multipleupload->user_id !== Auth::id()) {
            abort(403);
        }

        if ($multipleupload->filename && Storage::disk('public')->exists($multipleupload->filename)) {
            Storage::disk('public')->delete($multipleupload->filename);
        }

        $multipleupload->delete();

        return redirect()->route('uploads')->with('success', 'File berhasil dihapus.');
    }
}
