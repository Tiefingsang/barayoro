<?php
// app/Http/Controllers/CommentController.php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $commentable = $request->commentable_type::find($request->commentable_id);

        if (!$commentable) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $this->checkCompanyAccess($commentable);

        $comment = Comment::create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $this->getCompanyId(),
            'user_id' => auth()->id(),
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'status' => 'published',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $comment->load('user');

        if ($request->wantsJson()) {
            return response()->json($comment, 201);
        }

        return back()->with('success', 'Commentaire ajouté.');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        if (!$comment->canEdit()) {
            abort(403, 'Vous n\'avez pas le droit de modifier ce commentaire.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->content,
            'is_edited' => true,
            'edited_by' => auth()->id(),
            'edited_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($comment);
        }

        return back()->with('success', 'Commentaire mis à jour.');
    }

    public function destroy(Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        if (!$comment->canDelete()) {
            abort(403, 'Vous n\'avez pas le droit de supprimer ce commentaire.');
        }

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Commentaire supprimé.']);
        }

        return back()->with('success', 'Commentaire supprimé.');
    }

    public function pin(Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        if (!auth()->user()->hasRole(['admin', 'manager'])) {
            abort(403, 'Vous n\'avez pas le droit d\'épingler des commentaires.');
        }

        $comment->pin();

        return back()->with('success', 'Commentaire épinglé.');
    }

    public function unpin(Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        if (!auth()->user()->hasRole(['admin', 'manager'])) {
            abort(403, 'Vous n\'avez pas le droit de désépingler des commentaires.');
        }

        $comment->unpin();

        return back()->with('success', 'Commentaire désépinglé.');
    }

    public function resolve(Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        if (!auth()->user()->hasRole(['admin', 'manager'])) {
            abort(403, 'Vous n\'avez pas le droit de résoudre des commentaires.');
        }

        $comment->resolve();

        return back()->with('success', 'Commentaire marqué comme résolu.');
    }

    public function react(Request $request, Comment $comment)
    {
        $this->checkCompanyAccess($comment);

        $request->validate([
            'reaction' => 'required|string|in:👍,❤️,😂,😮,😢,😡',
        ]);

        $result = $comment->addReaction(auth()->id(), $request->reaction);

        if ($request->wantsJson()) {
            return response()->json(['added' => $result]);
        }

        return back();
    }
}
