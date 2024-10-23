<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index(Request $request, $parentId = null)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Fetch folders based on parent ID
        $folders = Folder::with(['subfolders', 'files'])
            ->where('parent_id', $parentId) // Only fetch folders that match the parent ID
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data folder berhasil diambil',
            'data' => $folders
        ], 200);
    }


    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        $folder = Folder::with(['subfolders', 'files'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data folder berhasil diambil',
            'data' => $folder
        ], 200);
    }
    public function create(Request $request)
    {
        // Memeriksa apakah pengguna terautentikasi
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id', // Parent folder ID yang opsional
        ]);

        // Membuat folder baru
        $folder = Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Folder berhasil dibuat',
            'data' => $folder
        ], 201);
    }

    public function move(Request $request, $id)
    {
        // Memeriksa apakah pengguna terautentikasi
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Validasi input
        $request->validate([
            'parent_id' => 'required|exists:folders,id', // ID folder induk yang valid
        ]);

        // Mencari folder berdasarkan ID
        $folder = Folder::findOrFail($id);

        // Memindahkan folder ke folder baru
        $folder->update([
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Folder berhasil dipindahkan',
            'data' => $folder
        ], 200);
    }

}
