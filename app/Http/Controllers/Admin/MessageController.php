<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = \App\Models\Message::latest()->paginate(10);
        return view('messages.index', compact('messages'));
    }

    public function destroy(\App\Models\Message $message)
    {
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Pesan berhasil dihapus!');
    }
}
