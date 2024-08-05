<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageEvent;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        event(new MessageEvent('hello world'));
        return response()->json(['status' => 'Message sent successfully']);
    }
}
