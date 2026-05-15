<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $messages = ContactMessage::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('fullname', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'today' => ContactMessage::whereDate('created_at', today())->count(),
            'this_month' => ContactMessage::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('admin.contact.index', compact('messages', 'keyword', 'limit', 'stats'));
    }

    public function show(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('admin.contact.show', compact('message'));
    }

    public function destroy(int $id)
    {
        ContactMessage::destroy($id);
        return back()->with('message', 'Đã xóa liên hệ');
    }
}
