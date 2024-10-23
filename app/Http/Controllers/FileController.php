<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        $files = File::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data file berhasil diambil',
            'data' => $files
        ], 200);
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        $file = File::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data file berhasil diambil',
            'data' => $file
        ], 200);
    }
    public function upload(Request $request)
    {
        // Memeriksa apakah pengguna terautentikasi
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Validasi file yang diupload
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // batas ukuran 2MB
            'folder_id' => 'required|exists:folders,id' // Validasi folder_id harus ada di tabel folders
        ]);

        // Menyimpan file
        $filePath = $request->file('file')->store('uploads', 'public');

        // Membuat entri baru di database
        $file = File::create([
            'folder_id' => $request->folder_id,
            'path' => $filePath,
            'name' => $request->file('file')->getClientOriginalName(),
            'size' => $request->file('file')->getSize(),
        ]);

        // Mengembalikan respons JSON sukses
        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil diupload',
            'data' => [
                'id' => $file->id,
                'name' => $file->name,
                'path' => Storage::url($filePath), // Mengembalikan path file
                'uploaded_at' => now(), // Menampilkan waktu upload
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Mencari file berdasarkan ID
        $file = File::findOrFail($id);

        // Memperbarui nama file
        $file->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Nama file berhasil diperbarui',
            'data' => $file
        ], 200);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Mencari file berdasarkan ID
        $file = File::findOrFail($id);

        // Menghapus file dari storage
        Storage::disk('public')->delete($file->path); // Pastikan path ada di dalam model

        // Menghapus entri dari database
        $file->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil dihapus',
        ], 200);
    }

    public function move(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Token tidak valid atau tidak terautentikasi'], 401);
        }

        // Validasi input
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
        ]);

        $file = File::findOrFail($id);

        $targetFolder = Folder::findOrFail($request->folder_id);

        $file->update([
            'folder_id' => $request->folder_id,
            // 'path' => $this->getNewFilePath($file->name, $targetFolder->name), // Memperbarui path sesuai folder baru
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'File berhasil dipindahkan ke folder baru',
            'data' => $file
        ], 200);
    }

    // Fungsi untuk mendapatkan path baru berdasarkan nama file dan folder
    // private function getNewFilePath($fileName, $folderName)
    // {
    //     return 'uploads/' . $folderName . '/' . $fileName;
    // }

}
