<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FileManagerController extends Controller
{
    /**
     * Afficher le gestionnaire de fichiers
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;
        $folderId = $request->get('folder', null);

        // Récupérer le dossier actuel
        $currentFolder = null;
        if ($folderId) {
            $currentFolder = File::where('company_id', $companyId)
                ->where('id', $folderId)
                ->where('type', 'folder')
                ->first();
        }

        // Récupérer les fichiers et dossiers
        $items = File::where('company_id', $companyId)
            ->where('parent_id', $folderId)
            ->orderByRaw("FIELD(type, 'folder', 'file')")
            ->orderBy('name')
            ->paginate(20);

        // Récupérer le chemin complet pour le breadcrumb
        $breadcrumbs = $this->getBreadcrumbs($folderId);

        // Récupérer la vue (grid ou list)
        $view = $request->cookie('file_view', 'grid');

        return view('files.index', compact('items', 'currentFolder', 'breadcrumbs', 'view'));
    }

    /**
     * Créer un dossier
     */
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:files,id'
        ]);

        $companyId = Auth::user()->company_id;

        // Vérifier si le dossier existe déjà
        $exists = File::where('company_id', $companyId)
            ->where('parent_id', $request->parent_id)
            ->where('name', $request->name)
            ->where('type', 'folder')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Un dossier avec ce nom existe déjà.');
        }

        File::create([
            'uuid' => Str::uuid(),
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'type' => 'folder',
            'parent_id' => $request->parent_id,
            'mime_type' => 'folder',
            'size' => 0,
            'path' => null,
        ]);

        return back()->with('success', 'Dossier créé avec succès.');
    }

    /**
     * Uploader un fichier
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // max 100MB
            'parent_id' => 'nullable|exists:files,id'
        ]);

        $companyId = Auth::user()->company_id;
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $mimeType = $file->getMimeType();

        // Générer un nom unique pour le stockage
        $filename = Str::uuid() . '.' . $extension;
        $path = $file->storeAs('files/' . $companyId, $filename, 'public');

        // Créer l'enregistrement en base de données
        File::create([
            'uuid' => Str::uuid(),
            'company_id' => $companyId,
            'user_id' => Auth::id(),
            'name' => $originalName,
            'type' => 'file',
            'parent_id' => $request->parent_id,
            'mime_type' => $mimeType,
            'size' => $size,
            'extension' => $extension,
            'path' => $path,
            'url' => Storage::url($path),
        ]);

        return back()->with('success', 'Fichier uploadé avec succès.');
    }

    /**
     * Télécharger un fichier
     */
    public function download(File $file)
    {
        $this->checkAccess($file);

        if ($file->type !== 'file') {
            return back()->with('error', 'Impossible de télécharger un dossier.');
        }

        return Storage::disk('public')->download($file->path, $file->name);
    }

    /**
     * Afficher un fichier (prévisualisation)
     */
    public function show(File $file)
    {
        $this->checkAccess($file);

        if ($file->type === 'folder') {
            return redirect()->route('files.index', ['folder' => $file->id]);
        }

        // Vérifier si le fichier peut être prévisualisé
        $previewable = in_array($file->extension, ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'txt', 'mp4', 'webm']);

        return view('files.show', compact('file', 'previewable'));
    }

    /**
     * Renommer un fichier/dossier
     */
    public function rename(Request $request, File $file)
    {
        $this->checkAccess($file);

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Vérifier si le nom existe déjà dans le même dossier
        $exists = File::where('company_id', $file->company_id)
            ->where('parent_id', $file->parent_id)
            ->where('name', $request->name)
            ->where('id', '!=', $file->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Un élément avec ce nom existe déjà.');
        }

        $file->update(['name' => $request->name]);

        return back()->with('success', 'Renommé avec succès.');
    }

    /**
     * Déplacer un fichier/dossier
     */
    public function move(Request $request, File $file)
    {
        $this->checkAccess($file);

        $request->validate([
            'target_folder_id' => 'nullable|exists:files,id'
        ]);

        // Vérifier qu'on ne déplace pas dans un sous-dossier de lui-même
        if ($file->type === 'folder') {
            $target = File::find($request->target_folder_id);
            if ($target && $this->isChild($file, $target)) {
                return back()->with('error', 'Impossible de déplacer un dossier dans un de ses sous-dossiers.');
            }
        }

        $file->update(['parent_id' => $request->target_folder_id]);

        return back()->with('success', 'Déplacé avec succès.');
    }

    /**
     * Supprimer un fichier/dossier
     */
    public function destroy(File $file)
    {
        $this->checkAccess($file);

        if ($file->type === 'file') {
            // Supprimer le fichier physique
            if ($file->path && Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        } else {
            // Supprimer récursivement les fichiers du dossier
            $this->deleteFolderRecursive($file);
        }

        $file->delete();

        return back()->with('success', 'Supprimé avec succès.');
    }

    /**
     * Rechercher des fichiers
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $companyId = Auth::user()->company_id;

        $items = File::where('company_id', $companyId)
            ->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query . '%')
                  ->orWhere('extension', 'like', '%' . $request->query . '%');
            })
            ->orderBy('type', 'desc')
            ->paginate(20);

        $breadcrumbs = collect([(object)['id' => null, 'name' => 'Recherche: ' . $request->query]]);
        $view = $request->cookie('file_view', 'grid');

        return view('files.index', compact('items', 'breadcrumbs', 'view'));
    }

    /**
     * Changer la vue (grid/list)
     */
    public function setView(Request $request)
    {
        $view = $request->get('view', 'grid');
        return redirect()->back()->withCookie(cookie('file_view', $view, 60 * 24 * 30));
    }

    /**
     * Récupérer le breadcrumb
     */
    private function getBreadcrumbs($folderId)
    {
        $breadcrumbs = collect();

        while ($folderId) {
            $folder = File::find($folderId);
            if (!$folder) break;

            $breadcrumbs->prepend((object)[
                'id' => $folder->id,
                'name' => $folder->name
            ]);

            $folderId = $folder->parent_id;
        }

        return $breadcrumbs;
    }

    /**
     * Vérifier si un dossier est enfant d'un autre
     */
    private function isChild(File $parent, File $child)
    {
        while ($child->parent_id) {
            if ($child->parent_id == $parent->id) {
                return true;
            }
            $child = File::find($child->parent_id);
            if (!$child) break;
        }
        return false;
    }

    /**
     * Supprimer récursivement un dossier
     */
    private function deleteFolderRecursive(File $folder)
    {
        $children = File::where('parent_id', $folder->id)->get();

        foreach ($children as $child) {
            if ($child->type === 'folder') {
                $this->deleteFolderRecursive($child);
            } else {
                if ($child->path && Storage::disk('public')->exists($child->path)) {
                    Storage::disk('public')->delete($child->path);
                }
                $child->delete();
            }
        }
    }








    /**
 * Upload en chunks (pour gros fichiers)
 */
public function uploadChunk(Request $request)
{
    $request->validate([
        'file' => 'required|file',
        'resumableIdentifier' => 'required|string',
        'resumableChunkNumber' => 'required|integer',
        'resumableTotalChunks' => 'required|integer',
        'parent_id' => 'nullable|exists:files,id'
    ]);

    $tempDir = storage_path('app/temp/' . $request->resumableIdentifier);
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $chunkFile = $tempDir . '/' . $request->resumableChunkNumber;
    $request->file('file')->move($tempDir, $request->resumableChunkNumber);

    if ($request->resumableChunkNumber == $request->resumableTotalChunks) {
        // Assembler les chunks
        $finalFile = $tempDir . '/final.' . $request->file('file')->getClientOriginalExtension();
        $handle = fopen($finalFile, 'wb');

        for ($i = 1; $i <= $request->resumableTotalChunks; $i++) {
            $chunk = fopen($tempDir . '/' . $i, 'rb');
            stream_copy_to_stream($chunk, $handle);
            fclose($chunk);
            unlink($tempDir . '/' . $i);
        }
        fclose($handle);

        // Uploader le fichier final
        $file = new \Illuminate\Http\UploadedFile(
            $finalFile,
            $request->file('file')->getClientOriginalName(),
            $request->file('file')->getMimeType(),
            null,
            true
        );

        $uploadRequest = new Request([
            'file' => $file,
            'parent_id' => $request->parent_id
        ]);

        $result = $this->upload($uploadRequest);

        // Nettoyer
        rmdir($tempDir);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => true]);
}

/**
 * Copier un fichier/dossier
 */
public function copy(Request $request, File $file)
{
    $this->checkAccess($file);

    $request->validate([
        'target_folder_id' => 'nullable|exists:files,id'
    ]);

    if ($file->type === 'file') {
        $newName = 'Copie de ' . $file->name;
        $newPath = 'files/' . $file->company_id . '/' . Str::uuid() . '.' . $file->extension;

        Storage::disk('public')->copy($file->path, $newPath);

        $copy = File::create([
            'uuid' => Str::uuid(),
            'company_id' => $file->company_id,
            'user_id' => Auth::id(),
            'name' => $newName,
            'type' => 'file',
            'parent_id' => $request->target_folder_id,
            'mime_type' => $file->mime_type,
            'size' => $file->size,
            'extension' => $file->extension,
            'path' => $newPath,
            'url' => Storage::url($newPath),
        ]);
    } else {
        $copy = $this->copyFolder($file, $request->target_folder_id);
    }

    return back()->with('success', 'Copie créée avec succès.');
}

/**
 * Copier récursivement un dossier
 */
private function copyFolder(File $folder, $newParentId = null)
{
    $newFolder = File::create([
        'uuid' => Str::uuid(),
        'company_id' => $folder->company_id,
        'user_id' => Auth::id(),
        'name' => 'Copie de ' . $folder->name,
        'type' => 'folder',
        'parent_id' => $newParentId,
        'mime_type' => 'folder',
        'size' => 0,
    ]);

    $children = File::where('parent_id', $folder->id)->get();
    foreach ($children as $child) {
        if ($child->type === 'folder') {
            $this->copyFolder($child, $newFolder->id);
        } else {
            $newPath = 'files/' . $folder->company_id . '/' . Str::uuid() . '.' . $child->extension;
            Storage::disk('public')->copy($child->path, $newPath);

            File::create([
                'uuid' => Str::uuid(),
                'company_id' => $child->company_id,
                'user_id' => Auth::id(),
                'name' => $child->name,
                'type' => 'file',
                'parent_id' => $newFolder->id,
                'mime_type' => $child->mime_type,
                'size' => $child->size,
                'extension' => $child->extension,
                'path' => $newPath,
                'url' => Storage::url($newPath),
            ]);
        }
    }

    return $newFolder;
}

/**
 * Créer une archive ZIP d'un dossier
 */
public function zip(File $folder)
{
    $this->checkAccess($folder);

    if ($folder->type !== 'folder') {
        return back()->with('error', 'Seuls les dossiers peuvent être compressés.');
    }

    $zip = new \ZipArchive();
    $zipName = $folder->name . '.zip';
    $zipPath = storage_path('app/temp/' . Str::uuid() . '.zip');

    if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
        $this->addFolderToZip($folder, $zip, '');
        $zip->close();

        // Uploader le fichier zip
        $file = new \Illuminate\Http\UploadedFile(
            $zipPath,
            $zipName,
            'application/zip',
            null,
            true
        );

        $uploadRequest = new Request([
            'file' => $file,
            'parent_id' => $folder->parent_id
        ]);

        $this->upload($uploadRequest);

        // Nettoyer
        unlink($zipPath);

        return back()->with('success', 'Dossier compressé avec succès.');
    }

    return back()->with('error', 'Erreur lors de la compression.');
}

/**
 * Ajouter un dossier à l'archive ZIP
 */
private function addFolderToZip(File $folder, \ZipArchive $zip, $path)
{
    $children = File::where('parent_id', $folder->id)->get();

    foreach ($children as $child) {
        if ($child->type === 'folder') {
            $this->addFolderToZip($child, $zip, $path . $child->name . '/');
        } else {
            $filePath = Storage::disk('public')->path($child->path);
            $zip->addFile($filePath, $path . $child->name);
        }
    }
}

/**
 * Partager un fichier/dossier
 */
public function share(Request $request, File $file)
{
    $this->checkAccess($file);

    $request->validate([
        'expires_at' => 'nullable|date|after:now',
        'password' => 'nullable|string|min:4'
    ]);

    $share = \App\Models\FileShare::create([
        'uuid' => Str::uuid(),
        'file_id' => $file->id,
        'user_id' => Auth::id(),
        'token' => Str::random(32),
        'password' => $request->password ? bcrypt($request->password) : null,
        'expires_at' => $request->expires_at,
    ]);

    return response()->json([
        'success' => true,
        'url' => route('files.shared', $share->token)
    ]);
}

/**
 * Afficher un fichier partagé
 */
public function shared($token, Request $request)
{
    $share = \App\Models\FileShare::where('token', $token)
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->firstOrFail();

    $file = $share->file;

    // Vérifier le mot de passe si nécessaire
    if ($share->password && !session('share_auth_' . $share->id)) {
        if ($request->isMethod('post')) {
            if (Hash::check($request->password, $share->password)) {
                session(['share_auth_' . $share->id => true]);
                return redirect()->route('files.shared', $token);
            }
            return back()->with('error', 'Mot de passe incorrect.');
        }

        return view('files.share-password', compact('share'));
    }

    if ($file->type === 'folder') {
        $items = File::where('parent_id', $file->id)->get();
        return view('files.shared-folder', compact('file', 'items'));
    }

    return view('files.shared-file', compact('file'));
}

/**
 * Ajouter aux favoris
 */
public function addFavorite(File $file)
{
    $this->checkAccess($file);

    \App\Models\FileFavorite::updateOrCreate([
        'user_id' => Auth::id(),
        'file_id' => $file->id
    ]);

    return back()->with('success', 'Ajouté aux favoris.');
}

/**
 * Retirer des favoris
 */
public function removeFavorite(File $file)
{
    $this->checkAccess($file);

    \App\Models\FileFavorite::where('user_id', Auth::id())
        ->where('file_id', $file->id)
        ->delete();

    return back()->with('success', 'Retiré des favoris.');
}

/**
 * Afficher les favoris
 */
public function favorites()
{
    $items = File::whereHas('favorites', function($q) {
        $q->where('user_id', Auth::id());
    })->paginate(20);

    return view('files.favorites', compact('items'));
}

/**
 * Afficher la corbeille
 */
public function trash()
{
    $items = File::where('company_id', Auth::user()->company_id)
        ->onlyTrashed()
        ->paginate(20);

    return view('files.trash', compact('items'));
}

/**
 * Restaurer un fichier
 */
public function restore($id)
{
    $file = File::withTrashed()->findOrFail($id);
    $this->checkAccess($file);

    $file->restore();

    return back()->with('success', 'Fichier restauré.');
}

/**
 * Supprimer définitivement
 */
public function forceDelete($id)
{
    $file = File::withTrashed()->findOrFail($id);
    $this->checkAccess($file);

    if ($file->type === 'file' && $file->path) {
        Storage::disk('public')->delete($file->path);
    }

    $file->forceDelete();

    return back()->with('success', 'Fichier supprimé définitivement.');
}

/**
 * Vider la corbeille
 */
public function emptyTrash()
{
    $files = File::where('company_id', Auth::user()->company_id)
        ->onlyTrashed()
        ->get();

    foreach ($files as $file) {
        if ($file->type === 'file' && $file->path) {
            Storage::disk('public')->delete($file->path);
        }
        $file->forceDelete();
    }

    return back()->with('success', 'Corbeille vidée.');
}

/**
 * Informations détaillées d'un fichier
 */
public function info(File $file)
{
    $this->checkAccess($file);

    return response()->json([
        'id' => $file->id,
        'name' => $file->name,
        'type' => $file->type,
        'size' => $file->formatted_size,
        'mime_type' => $file->mime_type,
        'created_at' => $file->created_at->format('d/m/Y H:i'),
        'updated_at' => $file->updated_at->format('d/m/Y H:i'),
        'owner' => $file->user->name,
        'url' => $file->type === 'file' ? $file->url : null,
        'children_count' => $file->type === 'folder' ? File::where('parent_id', $file->id)->count() : 0,
    ]);
}

/**
 * Statistiques d'utilisation
 */
public function stats()
{
    $companyId = Auth::user()->company_id;

    $stats = [
        'total_files' => File::where('company_id', $companyId)->where('type', 'file')->count(),
        'total_folders' => File::where('company_id', $companyId)->where('type', 'folder')->count(),
        'total_size' => File::where('company_id', $companyId)->where('type', 'file')->sum('size'),
        'total_size_formatted' => $this->formatBytes(File::where('company_id', $companyId)->where('type', 'file')->sum('size')),
        'disk_usage' => $this->formatBytes(Storage::disk('public')->size('files/' . $companyId)),
        'most_used_extensions' => File::where('company_id', $companyId)
            ->where('type', 'file')
            ->select('extension', DB::raw('count(*) as count'))
            ->groupBy('extension')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get(),
        'recent_files' => File::where('company_id', $companyId)
            ->where('type', 'file')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(),
    ];

    return view('files.stats', compact('stats'));
}

/**
 * Formater les bytes
 */
private function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}




    /**
     * Vérifier l'accès au fichier
     */
    private function checkAccess(File $file)
    {
        if ($file->company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
