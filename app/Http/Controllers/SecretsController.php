<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use App\Models\Secret;
use Illuminate\Support\Facades\Auth;

class SecretsController extends Controller
{
    public function index(): Responsable
    {
        return Inertia::render('Secrets',
            [
                'activeSecrets' => Secret::ownedByUser()->active()->get(),
                'inactiveSecrets' => Secret::ownedByUser()->inactive()->get(),
            ]);
    }

    public function generate(Request $request) {
        $request->validate([
            "message" => "required",
        ]);

        $message = $request->input("message");
        $expiry = $request->input("expires_at");

        $secret = new Secret();
        $secret->message = $message;
        $secret->expires_at = $expiry;
        $secret->user_id = Auth::user()->id;

        $secret->save();

        return Inertia::render("Secrets",[
            'activeSecrets' => Secret::ownedByUser()->active()->get(),
            'inactiveSecrets' => Secret::ownedByUser()->inactive()->get(),
            "newLink" => $secret->generateLink(),
            "token" => $secret->token
        ]);
    }

    public function read(String $token) {
        $secret = Secret::active()->find($token);

        if (!$secret) {
            //@NOTE We don't return back anything more informative as this would be a public endpoint, and we don't want potential bad actors to have insight into tokens.
            return response("Secret not found.", 404);
        }

        $secret->markRead();

        return Inertia::render("Secrets/SecretsView", [
            'message' => $secret->message,
            'senderName' => $secret->user->name,
        ]);
        /* return response($secret->message, 200); */
    }
}
