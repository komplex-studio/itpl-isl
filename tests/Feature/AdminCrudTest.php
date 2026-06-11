<?php

namespace Tests\Feature;

use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Fixture;
use App\Models\MedalTally;
use App\Models\News;
use App\Models\Registration;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(User::first());
    }

    /** Every admin index and create page renders without error. */
    public function test_admin_index_and_create_pages_render(): void
    {
        foreach (['sports', 'events', 'athletes', 'news', 'certificates', 'medal-tallies', 'registrations', 'fixtures'] as $resource) {
            $this->get("/admin/$resource")->assertOk();
            $this->get("/admin/$resource/create")->assertOk();
        }
    }

    public function test_sport_crud(): void
    {
        $this->post('/admin/sports', [
            'name' => 'Hockey', 'icon' => '🏑', 'color' => 'victory', 'format' => 'league',
            'tagline' => 'Stick to glory', 'image' => 'https://example.com/h.jpg', 'description' => 'Field hockey.',
        ])->assertRedirect(route('admin.sports.index'));

        $sport = Sport::where('name', 'Hockey')->firstOrFail();
        $this->assertSame('hockey', $sport->slug);

        $this->put("/admin/sports/{$sport->slug}", [
            'name' => 'Field Hockey', 'icon' => '🏑', 'color' => 'ink', 'format' => 'knockout',
        ])->assertRedirect(route('admin.sports.index'));
        $this->assertSame('ink', $sport->fresh()->color);

        $this->delete("/admin/sports/{$sport->fresh()->slug}")->assertRedirect(route('admin.sports.index'));
        $this->assertDatabaseMissing('sports', ['name' => 'Field Hockey']);
    }

    public function test_sport_with_events_cannot_be_deleted(): void
    {
        $sport = Sport::has('events')->firstOrFail();
        $this->delete("/admin/sports/{$sport->slug}");
        $this->assertDatabaseHas('sports', ['id' => $sport->id]);
    }

    public function test_athlete_store_auto_generates_code(): void
    {
        $this->post('/admin/athletes', [
            'name' => 'Test Runner', 'gender' => 'F', 'dob' => '2002-04-01',
            'state' => 'Goa', 'city' => 'Panaji', 'email' => 'test.runner@isl.test',
        ])->assertRedirect(route('admin.athletes.index'));

        $athlete = Athlete::where('email', 'test.runner@isl.test')->firstOrFail();
        $this->assertMatchesRegularExpression('/^ISL26-\d{6}$/', $athlete->code);
    }

    public function test_certificate_store_auto_generates_number(): void
    {
        $this->post('/admin/certificates', [
            'athlete_id' => Athlete::first()->id, 'event_id' => Event::first()->id,
            'type' => 'winner', 'title' => 'Gold — Test', 'issued_at' => '2026-05-13',
        ])->assertRedirect(route('admin.certificates.index'));

        $this->assertStringStartsWith('ISL-CERT-2026-', Certificate::latest('id')->first()->number);
    }

    public function test_event_news_medal_registration_fixture_crud(): void
    {
        // Event
        $this->post('/admin/events', [
            'sport_id' => Sport::first()->id, 'name' => 'Demo Cup', 'season' => '2026',
            'city' => 'Pune', 'state' => 'Maharashtra', 'venue' => 'Arena', 'start_date' => '2026-08-01',
            'end_date' => '2026-08-03', 'status' => 'upcoming', 'prize_pool' => 100000, 'registration_open' => '1',
        ])->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', ['name' => 'Demo Cup', 'slug' => 'demo-cup']);

        // News
        $this->post('/admin/news', [
            'title' => 'Demo Story', 'category' => 'Season', 'published_at' => '2026-06-01',
            'excerpt' => 'x', 'body' => 'body text',
        ])->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', ['slug' => 'demo-story']);

        // Medal tally
        $this->post('/admin/medal-tallies', ['state' => 'Sikkim', 'gold' => 1, 'silver' => 2, 'bronze' => 3])
            ->assertRedirect(route('admin.medal-tallies.index'));
        $tally = MedalTally::where('state', 'Sikkim')->firstOrFail();
        $this->delete("/admin/medal-tallies/{$tally->id}")->assertRedirect();
        $this->assertDatabaseMissing('medal_tallies', ['state' => 'Sikkim']);

        // Registration full CRUD + inline status quick-action
        $this->post('/admin/registrations', [
            'athlete_id' => Athlete::first()->id, 'event_id' => Event::first()->id,
            'category' => 'Open', 'status' => 'pending',
        ])->assertRedirect(route('admin.registrations.index'));
        $reg = Registration::latest('id')->first();
        $this->patch("/admin/registrations/{$reg->id}/status", ['status' => 'approved'])->assertRedirect();
        $this->assertSame('approved', $reg->fresh()->status);
        $this->delete("/admin/registrations/{$reg->id}")->assertRedirect();

        // Fixture full CRUD + inline result quick-action
        $a = Athlete::take(2)->get();
        $this->post('/admin/fixtures', [
            'event_id' => Event::first()->id, 'round' => 'Heat', 'round_order' => 1, 'slot' => 1,
            'athlete_a_id' => $a[0]->id, 'athlete_b_id' => $a[1]->id, 'status' => 'scheduled',
        ])->assertRedirect(route('admin.fixtures.index'));
        $fx = Fixture::latest('id')->first();
        $this->patch("/admin/fixtures/{$fx->id}/result", ['score_a' => '3', 'score_b' => '1', 'winner_id' => $a[0]->id])->assertRedirect();
        $this->assertSame('completed', $fx->fresh()->status);
        $this->delete("/admin/fixtures/{$fx->id}")->assertRedirect();
    }

    public function test_validation_errors_are_returned(): void
    {
        $this->from('/admin/sports/create')
            ->post('/admin/sports', ['name' => ''])
            ->assertRedirect('/admin/sports/create')
            ->assertSessionHasErrors(['name', 'icon']);
    }

    public function test_edit_pages_render_for_existing_records(): void
    {
        $this->get('/admin/sports/'.Sport::first()->slug.'/edit')->assertOk();
        $this->get('/admin/events/'.Event::first()->slug.'/edit')->assertOk();
        $this->get('/admin/news/'.News::first()->slug.'/edit')->assertOk();
        $this->get('/admin/athletes/'.Athlete::first()->code.'/edit')->assertOk();
        $this->get('/admin/certificates/'.Certificate::first()->number.'/edit')->assertOk();
        $this->get('/admin/registrations/'.Registration::first()->id.'/edit')->assertOk();
        $this->get('/admin/fixtures/'.Fixture::first()->id.'/edit')->assertOk();
        $this->get('/admin/medal-tallies/'.MedalTally::first()->id.'/edit')->assertOk();
    }
}
