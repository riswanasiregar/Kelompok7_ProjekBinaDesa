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
        try {
            $request->validate([
                'filename' => 'required|array',
                'filename.*' => 'required|file|mimes:doc,docx,pdf,jpg,jpeg,png,gif,bmp,webp|max:10240'
            ]);

            if (!$request->hasFile('filename')) {
                return back()->withErrors(['filename' => 'File tidak ditemukan.']);
            }

            $uploadedFiles = [];
            
            foreach ($request->file('filename') as $file) {
                if ($file->isValid()) {
                    // Generate unique filename
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    
                    // Store file
                    $storedPath = $file->storeAs('multiple-uploads', $filename, 'public');

                    if ($storedPath) {
                        $upload = Multipleuploads::create([
                            'filename' => $storedPath,
                            'user_id' => Auth::id(),
                        ]);
                        
                        $uploadedFiles[] = $originalName;
                    }
                } else {
                    return back()->withErrors(['filename' => 'File ' . $file->getClientOriginalName() . ' tidak valid.']);
                }
            }

            $message = 'File berhasil diunggah: ' . implode(', ', $uploadedFiles);
            return redirect()->route('uploads')->with('success', $message);
            
        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            return back()->withErrors(['filename' => 'Terjadi kesalahan saat upload: ' . $e->getMessage()]);
        }
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
