<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyCompletion;
use Carbon\Carbon;

class DailyChallengeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Check if user already completed today's challenge
        $completion = DailyCompletion::where('user_id', $user->id)
                                     ->where('date', $today)
                                     ->first();

        // Determine today's game based on day of year OR Admin Override
        // 0: Tic-Tac-Toe, 1: Rock-Paper-Scissors, 2: Memory Match, 3: Snake & Ladders, 4: Mancala
        $games = ['tictactoe', 'rps', 'memory', 'snake_ladders', 'mancala'];
        
        // ADMIN OVERRIDE CHECK
        if (\Illuminate\Support\Facades\Cache::has('daily_game_override')) {
            $currentGame = \Illuminate\Support\Facades\Cache::get('daily_game_override');
        } else {
            $gameIndex = $today->dayOfYear % 5;
            $currentGame = $games[$gameIndex];
        }

        return view('community.daily-challenge', compact('user', 'completion', 'currentGame'));
    }

    public function complete(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Validate request
        $request->validate([
            'game_type' => 'required|string',
            'result' => 'required|in:win', // Only award for wins
        ]);

        // 2. Check if already completed
        $exists = DailyCompletion::where('user_id', $user->id)
                                 ->where('date', $today)
                                 ->exists();

        if ($exists) {
            return response()->json(['message' => 'You have already claimed your daily reward!'], 400);
        }

        // 3. Award Coins
        $coinsEarned = 100; // Fixed reward for now
        
        $user->kukus_coins += $coinsEarned;
        $user->save();

        // 4. Log Completion
        DailyCompletion::create([
            'user_id' => $user->id,
            'date' => $today,
            'game_type' => $request->game_type,
            'coins_earned' => $coinsEarned,
        ]);

        return response()->json([
            'message' => 'Congratulations! You earned ' . $coinsEarned . ' Kukus Coins!',
            'new_balance' => $user->kukus_coins
        ]);
    }

    // ADMIN ONLY: Set Daily Game Manually
    public function setGame(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'game' => 'required|in:tictactoe,rps,memory,snake_ladders,mancala,reset'
        ]);

        if ($request->game === 'reset') {
            \Illuminate\Support\Facades\Cache::forget('daily_game_override');
            return back()->with('success', 'Daily game reset to automatic rotation.');
        }

        \Illuminate\Support\Facades\Cache::put('daily_game_override', $request->game, 60 * 24); // Cache for 24 hours
        return back()->with('success', 'Daily game manually set to ' . ucfirst($request->game));
    }
}
