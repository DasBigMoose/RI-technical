<?php
namespace Tests\Feature;

use App\Models\Secret;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class SecretsTest extends TestCase {
    use DatabaseTransactions;
    use WithFaker;

    public function test_secrets_page_can_be_rendered() {
        $this->actingAs(User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $response = $this->get('/secrets');

        $response->assertStatus(200);
    }

    public function test_secrets_can_be_generated() {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $expires_at = Carbon::tomorrow();
        $message = $this->faker()->text(255);

        $response = $this->post('/secrets', [
            'message' => $message,
            "expires_at" => $expires_at
        ]);

        $content = $response->inertiaProps();

        $token = $content['token'];
        $this->assertDatabaseHas('Secrets',
            [
                'token' => $token,
                "expires_at" => $expires_at,
                "message" => $message,
                "accessed_at" => null,
                "user_id" => $user->id
            ]);

        $this->assertStringContainsString($token, $content['newLink']);

        $response->assertStatus(200);
    }

    public function test_secrets_expire() {
        $secret = Secret::factory()->make(['expires_at' => Carbon::yesterday()]);
        $secret->save();

        $response = $this->get("/secrets/".$secret->token);

        $response->assertStatus(404);

        $secret = Secret::factory()->make(['expires_at' => Carbon::tomorrow()]);
        $secret->save();

        $response = $this->get("/secrets/".$secret->token);

        $response->assertStatus(200);
    }

    public function test_secrets_read_once() {
        $secret = Secret::factory()->make();
        $secret->save();

        $response = $this->get("/secrets/".$secret->token);

        $response->assertStatus(200);
        $response->assertSee($secret->message);

        $response = $this->get("/secrets/".$secret->token);

        $response->assertStatus(404);
    }
}
