<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function contact()
    {
        return view('client.pages.contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'fullname' => 'nullable|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'message' => 'required|string|min:10|max:2000',
        ]);

        $data['fullname'] = trim((string) ($data['fullname'] ?? ''));
        if ($data['fullname'] === '') {
            $data['fullname'] = 'Khách hàng';
        }

        ContactMessage::create($data);

        return back()->with('message', 'Đã gửi liên hệ thành công. Chúng tôi sẽ phản hồi sớm.');
    }
}
