<?php
// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('read') && $request->read === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20);

        $stats = [
            'total' => Notification::where('user_id', Auth::id())->count(),
            'unread' => Notification::where('user_id', Auth::id())->where('is_read', false)->count(),
            'by_type' => Notification::where('user_id', Auth::id())
                ->select('type', \DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get(),
        ];

        return view('notifications.index', compact('notifications', 'stats'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marquée comme lue');
    }


    public function recent()
{
    $notifications = Notification::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'is_read' => $notification->is_read,
                'action_url' => $notification->action_url,
                'time_ago' => $notification->created_at->diffForHumans(),
            ];
        });

    $unreadCount = Notification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->count();

    return response()->json([
        'notifications' => $notifications,
        'unread_count' => $unreadCount,
    ]);
}
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
}
