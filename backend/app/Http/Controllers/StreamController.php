<?php

namespace App\Http\Controllers;

use App\Events\IceCandidate;
use App\Events\StreamAnswer;
use App\Events\StreamEnded;
use App\Events\StreamOffer;
use App\Models\StreamSession;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    // Start stream + send offer
    public function sendOffer(Request $request)
    {
        $request->validate([
            'offer' => 'required|array',
            'mode'  => 'required|in:camera,screen,mic',
        ]);

        // Save session to database
        StreamSession::create([
            'user_id'    => auth()->id(),
            'mode'       => $request->mode,
            'started_at' => now(),
        ]);

        // Broadcast offer to viewer via Reverb
        broadcast(new StreamOffer($request->offer));

        return response()->json(['status' => 'offer sent']);
    }

    // Receive answer from viewer
    public function sendAnswer(Request $request)
    {
        $request->validate(['answer' => 'required|array']);

        broadcast(new StreamAnswer($request->answer));

        return response()->json(['status' => 'answer sent']);
    }

    // Send ICE candidate
    public function sendIceCandidate(Request $request)
    {
        $request->validate(['candidate' => 'required|array']);

        broadcast(new IceCandidate($request->candidate));

        return response()->json(['status' => 'candidate sent']);
    }

    // Stop stream + save duration
    public function stopStream()
    {
        $session = StreamSession::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if ($session) {
            $session->update([
                'ended_at'         => now(),
                'duration_seconds' => now()->diffInSeconds($session->started_at),
            ]);
        }

        broadcast(new StreamEnded());

        return response()->json(['status' => 'stream ended']);
    }

    // Get stream history
    public function history()
    {
        $sessions = StreamSession::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($sessions);
    }
}