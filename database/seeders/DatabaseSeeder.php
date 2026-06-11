<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Fixture;
use App\Models\MedalTally;
use App\Models\News;
use App\Models\Registration;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /** running athlete code counter */
    private int $codeSeq = 4000;

    /**
     * Curated, hotlink-stable photographs for the image-led landing concept, keyed by sport.
     * Generic disciplines use Unsplash; the India-specific Kabaddi and Kusti (mud-akhara
     * pehlwani) use authentic Wikimedia Commons images so they read true to the sport.
     */
    private const IMAGES = [
        'Boxing' => 'https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?auto=format&fit=crop&w=1200&q=80',
        'Athletics' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=1200&q=80',
        'Kabaddi' => 'https://commons.wikimedia.org/wiki/Special:FilePath/A%20Kabaddi%20match%20at%202006%20Asian%20Games.jpg?width=1200',
        'Karate' => 'https://images.unsplash.com/photo-1555597673-b21d5c935865?auto=format&fit=crop&w=1200&q=80',
        'Football' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=1200&q=80',
        'Volleyball' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?auto=format&fit=crop&w=1200&q=80',
        'Kusti' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Kushti%20(in%20Bharatpur%20March%202013).jpg?width=1200',
        'Badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1200&q=80',
    ];

    public function run(): void
    {
        User::query()->create([
            'name' => 'League Admin',
            'email' => 'admin@isl.test',
            'password' => Hash::make('password'),
        ]);

        $sports = $this->seedSports();
        $athletes = $this->seedAthletes();
        $events = $this->seedEvents($sports);

        $this->seedRegistrations($events, $athletes);
        $this->seedBrackets($events, $athletes);
        $this->seedMedalTally();
        $this->seedCertificates($events, $athletes);
        $this->seedNews();
    }

    private function seedSports(): array
    {
        // [name, icon, color, format, tagline, description override (null = generic template)]
        $rows = [
            ['Boxing', '🥊', 'saffron', 'knockout', 'Punch above your weight', null],
            ['Athletics', '🏃', 'saffron', 'league', 'Faster, higher, stronger', null],
            ['Kabaddi', '🤾', 'victory', 'league', 'Raid. Tackle. Repeat.', null],
            ['Karate', '🥋', 'ink', 'knockout', 'Discipline in every strike',
                'WKF-style karate under the Karate India Organisation — kumite bouts and kata across Senior, U-21, Junior, Cadet and Sub-Junior categories, contested on the tatami for national honours.'],
            ['Football', '⚽', 'victory', 'league', 'The beautiful game', null],
            ['Volleyball', '🏐', 'saffron', 'league', 'Spike. Set. Dominate.',
                'Indoor six-a-side volleyball under the Volleyball Federation of India — the fast, high-flying court game powering the National Volleyball Championship and the Prime Volleyball League.'],
            ['Kusti', '🤼', 'ink', 'knockout', 'Strength from the soil',
                'Kusti (pehlwani) — India\'s traditional wrestling fought in the earthen akhara. Pehlwans grapple in a clay pit tended with oil, ghee and turmeric, a discipline rooted in the akharas of Varanasi, Punjab and Haryana.'],
            ['Badminton', '🏸', 'ink', 'knockout', 'Smash your limits', null],
        ];

        $out = [];
        foreach ($rows as [$name, $icon, $color, $format, $tagline, $description]) {
            $out[$name] = Sport::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => self::IMAGES[$name] ?? null,
                'icon' => $icon,
                'color' => $color,
                'format' => $format,
                'tagline' => $tagline,
                'description' => $description
                    ?? "National-level $name competition under the Indian Sports League, bringing together the country's finest talent across every state and union territory.",
            ]);
        }

        return $out;
    }

    private function seedEvents(array $sports): array
    {
        $defs = [
            ['Boxing', 'National Boxing Championship', 'Nahan', 'Himachal Pradesh', 'Chaudhary Sports Complex', '2026-06-28', '2026-07-03', 'ongoing', true, 2500000, 'from-saffron-600 to-ink-900'],
            ['Athletics', 'Indian Grand Prix Athletics', 'Bhubaneswar', 'Odisha', 'Kalinga Stadium', '2026-05-10', '2026-05-13', 'completed', false, 1500000, 'from-saffron-500 to-ink-800'],
            ['Kabaddi', 'Pro Kabaddi National Series', 'Patna', 'Bihar', 'Patliputra Sports Complex', '2026-08-02', '2026-08-12', 'upcoming', true, 3000000, 'from-victory-600 to-ink-900'],
            ['Karate', 'National Karate Championship', 'Bhopal', 'Madhya Pradesh', 'Tatya Tope Stadium', '2026-07-18', '2026-07-21', 'upcoming', true, 1200000, 'from-ink-700 to-ink-950'],
            ['Football', 'Indian Super Football Cup', 'Margao', 'Goa', 'Fatorda Stadium', '2026-06-30', '2026-07-12', 'ongoing', true, 3500000, 'from-victory-600 to-ink-900'],
            ['Volleyball', 'National Volleyball Championship', 'Chennai', 'Tamil Nadu', 'Jawaharlal Nehru Indoor Stadium', '2026-08-22', '2026-08-27', 'upcoming', true, 1800000, 'from-saffron-500 to-ink-800'],
            ['Kusti', 'Bharat Kesari Kushti Dangal', 'Sonipat', 'Haryana', 'Chhotu Ram Akhara', '2026-07-14', '2026-07-19', 'upcoming', true, 2000000, 'from-ink-700 to-ink-950'],
            ['Badminton', 'All India Badminton Masters', 'Hyderabad', 'Telangana', 'Gachibowli Indoor Stadium', '2026-09-05', '2026-09-10', 'upcoming', true, 2200000, 'from-ink-700 to-ink-950'],
        ];

        $out = [];
        foreach ($defs as [$sportName, $name, $city, $state, $venue, $start, $end, $status, $regOpen, $prize, $gradient]) {
            $event = Event::create([
                'sport_id' => $sports[$sportName]->id,
                'name' => $name.' 2026',
                'slug' => Str::slug($name.' 2026'),
                'image' => self::IMAGES[$sportName] ?? null,
                'season' => '2026',
                'city' => $city,
                'state' => $state,
                'venue' => $venue,
                'start_date' => $start,
                'end_date' => $end,
                'status' => $status,
                'registration_open' => $regOpen,
                'prize_pool' => $prize,
                'gradient' => $gradient,
                'summary' => "The $name 2026 gathers India's elite $sportName athletes in $city, $state. Six days of national-grade competition, live scoring and digital certification for every participant.",
            ]);
            // key by both the short def name and the stored name for convenient lookups
            $out[$name] = $event;
            $out[$event->name] = $event;
        }

        return $out;
    }

    private function seedAthletes(): array
    {
        $first = ['Arjun', 'Vikram', 'Rahul', 'Sandeep', 'Manish', 'Deepak', 'Rohit', 'Karan', 'Suresh', 'Amit',
            'Nikhil', 'Pawan', 'Gurpreet', 'Vijender', 'Lovlina', 'Nikhat', 'Mary', 'Saweety', 'Pooja', 'Simranjit',
            'Priya', 'Anjali', 'Sneha', 'Kavya', 'Ritu', 'Neeraj', 'Bajrang', 'Ravi', 'Sumit', 'Anshu',
            'Hima', 'Dutee', 'Jyothi', 'Sakshi', 'Vinesh', 'Mirabai', 'Jeremy', 'Lakshya', 'Satwik', 'Chirag'];
        $last = ['Singh', 'Kumar', 'Sharma', 'Patel', 'Reddy', 'Nair', 'Das', 'Yadav', 'Gowda', 'Borgohain',
            'Zareen', 'Kom', 'Boora', 'Rani', 'Punia', 'Dahiya', 'Chanu', 'Sen', 'Lal', 'Verma',
            'Phogat', 'Mondal', 'Bhati', 'Saikia', 'Devi', 'Chopra', 'Sindhu', 'Rankireddy', 'Shetty', 'Malik'];
        $states = ['Haryana', 'Punjab', 'Manipur', 'Kerala', 'Maharashtra', 'Tamil Nadu', 'Karnataka', 'Delhi',
            'Uttar Pradesh', 'Assam', 'Odisha', 'Telangana', 'West Bengal', 'Rajasthan', 'Himachal Pradesh'];
        $cities = ['Rohtak', 'Patiala', 'Imphal', 'Kochi', 'Pune', 'Chennai', 'Bengaluru', 'New Delhi',
            'Lucknow', 'Guwahati', 'Cuttack', 'Hyderabad', 'Kolkata', 'Jaipur', 'Shimla'];
        $tints = ['from-saffron-400 to-saffron-600', 'from-ink-500 to-ink-800', 'from-victory-400 to-victory-600',
            'from-saffron-500 to-ink-700', 'from-ink-400 to-victory-600'];

        $out = [];
        for ($i = 0; $i < 48; $i++) {
            $gender = $i % 3 === 0 ? 'F' : 'M';
            $stateIdx = $i % count($states);
            $name = $first[$i % count($first)].' '.$last[($i * 3) % count($last)];

            $out[] = Athlete::create([
                'code' => $this->nextCode(),
                'name' => $name,
                'gender' => $gender,
                'dob' => Carbon::create(2000 + ($i % 8), ($i % 12) + 1, ($i % 27) + 1),
                'state' => $states[$stateIdx],
                'city' => $cities[$stateIdx],
                'email' => Str::slug($name, '.').'@athlete.isl.test',
                'phone' => '+91 9'.str_pad((string) (800000000 + $i * 137), 9, '0', STR_PAD_LEFT),
                'avatar_tint' => $tints[$i % count($tints)],
                'bio' => 'State-level champion representing '.$states[$stateIdx].', training under the SAI national development programme.',
            ]);
        }

        return $out;
    }

    private function nextCode(): string
    {
        return 'ISL26-'.str_pad((string) (++$this->codeSeq), 6, '0', STR_PAD_LEFT);
    }

    private function seedRegistrations(array $events, array $athletes): void
    {
        $categories = [
            'National Boxing Championship 2026' => ['Men 57kg', 'Men 63kg', 'Men 71kg', 'Women 50kg', 'Women 60kg'],
            'Indian Grand Prix Athletics 2026' => ['100m', '400m', 'Javelin', 'Long Jump'],
            'Pro Kabaddi National Series 2026' => ['Senior Men', 'Senior Women'],
            'National Karate Championship 2026' => ['Kumite -60kg', 'Kumite -67kg', 'Kumite -75kg', 'Kata Individual', 'Kata Team'],
            'Indian Super Football Cup 2026' => ["Men's", "Women's"],
            'National Volleyball Championship 2026' => ['Senior Men', 'Senior Women'],
            'Bharat Kesari Kushti Dangal 2026' => ['Freestyle 65kg', 'Freestyle 74kg', 'Greco-Roman 60kg', 'Women 53kg'],
            'All India Badminton Masters 2026' => ['Singles', 'Doubles'],
        ];

        $statuses = ['approved', 'approved', 'approved', 'pending', 'pending', 'rejected'];

        $a = 0;
        $seen = [];
        foreach ($events as $event) {
            if (isset($seen[$event->id])) {
                continue; // events array is double-keyed; process each once
            }
            $seen[$event->id] = true;

            $cats = $categories[$event->name] ?? ['Open'];
            $count = $event->status === 'completed' ? 10 : 12;
            for ($n = 0; $n < $count; $n++) {
                $athlete = $athletes[$a % count($athletes)];
                $a++;
                $status = $event->status === 'completed' ? 'approved' : $statuses[$n % count($statuses)];
                Registration::create([
                    'athlete_id' => $athlete->id,
                    'event_id' => $event->id,
                    'category' => $cats[$n % count($cats)],
                    'status' => $status,
                    'registered_at' => $event->start_date->copy()->subDays(30 - $n),
                    'notes' => null,
                ]);
            }
        }
    }

    private function seedBrackets(array $events, array $athletes): void
    {
        // Fully-progressed bracket (Boxing, ongoing) and a completed one (Athletics final done).
        $this->buildKnockout($events['National Boxing Championship'], array_slice($athletes, 0, 8), progressTo: 'semifinal');
        $this->buildKnockout($events['Indian Grand Prix Athletics'], array_slice($athletes, 12, 8), progressTo: 'done');
    }

    /**
     * Build an 8-athlete single-elimination bracket.
     * $progressTo: 'quarter' | 'semifinal' | 'final' | 'done'
     */
    private function buildKnockout(Event $event, array $eight, string $progressTo): void
    {
        $stages = ['quarter', 'semifinal', 'final', 'done'];
        $reached = array_search($progressTo, $stages, true);

        $venue = $event->venue;
        $day = $event->start_date->copy();

        // Quarter-finals (round_order 1)
        $qfWinners = [];
        for ($i = 0; $i < 4; $i++) {
            $a = $eight[$i * 2];
            $b = $eight[$i * 2 + 1];
            $completed = $reached >= 1;
            $winner = $i % 2 === 0 ? $a : $b;
            $qfWinners[] = $winner;
            Fixture::create([
                'event_id' => $event->id,
                'round' => 'Quarter-final',
                'round_order' => 1,
                'slot' => $i + 1,
                'athlete_a_id' => $a->id,
                'athlete_b_id' => $b->id,
                'scheduled_at' => $day->copy()->setTime(14 + $i, 0),
                'venue' => "$venue · Ring A",
                'status' => $completed ? 'completed' : 'scheduled',
                'winner_id' => $completed ? $winner->id : null,
                'score_a' => $completed ? ($i % 2 === 0 ? '5' : '0') : null,
                'score_b' => $completed ? ($i % 2 === 0 ? '0' : '5') : null,
            ]);
        }

        // Semi-finals (round_order 2)
        $day->addDays(2);
        $sfWinners = [];
        for ($i = 0; $i < 2; $i++) {
            $a = $qfWinners[$i * 2];
            $b = $qfWinners[$i * 2 + 1];
            $completed = $reached >= 2;
            $live = $reached === 1 && $i === 0; // first SF live while bracket sits at semifinal stage
            $winner = $a;
            $sfWinners[] = $winner;
            Fixture::create([
                'event_id' => $event->id,
                'round' => 'Semi-final',
                'round_order' => 2,
                'slot' => $i + 1,
                'athlete_a_id' => $a?->id,
                'athlete_b_id' => $b?->id,
                'scheduled_at' => $day->copy()->setTime(17 + $i, 0),
                'venue' => "$venue · Centre Ring",
                'status' => $completed ? 'completed' : ($live ? 'live' : 'scheduled'),
                'winner_id' => $completed ? $winner->id : null,
                'score_a' => $completed ? '4' : ($live ? '2' : null),
                'score_b' => $completed ? '1' : ($live ? '1' : null),
            ]);
        }

        // Final (round_order 3)
        $day->addDays(2);
        $completed = $reached >= 3;
        $finalA = $sfWinners[0] ?? null;
        $finalB = $sfWinners[1] ?? null;
        Fixture::create([
            'event_id' => $event->id,
            'round' => 'Final',
            'round_order' => 3,
            'slot' => 1,
            'athlete_a_id' => $reached >= 2 ? $finalA?->id : null,
            'athlete_b_id' => $reached >= 2 ? $finalB?->id : null,
            'scheduled_at' => $day->copy()->setTime(19, 0),
            'venue' => "$venue · Centre Ring",
            'status' => $completed ? 'completed' : 'scheduled',
            'winner_id' => $completed ? $finalA?->id : null,
            'score_a' => $completed ? '5' : null,
            'score_b' => $completed ? '2' : null,
        ]);
    }

    private function seedMedalTally(): void
    {
        $rows = [
            ['Haryana', 14, 9, 11],
            ['Punjab', 11, 12, 8],
            ['Manipur', 10, 7, 9],
            ['Kerala', 8, 10, 12],
            ['Maharashtra', 7, 9, 13],
            ['Tamil Nadu', 6, 8, 7],
            ['Uttar Pradesh', 6, 5, 9],
            ['Karnataka', 5, 7, 6],
            ['Delhi', 5, 4, 8],
            ['Assam', 4, 6, 5],
            ['Odisha', 3, 5, 7],
            ['West Bengal', 3, 4, 6],
        ];
        foreach ($rows as [$state, $g, $s, $b]) {
            MedalTally::create(['state' => $state, 'gold' => $g, 'silver' => $s, 'bronze' => $b]);
        }
    }

    private function seedCertificates(array $events, array $athletes): void
    {
        $completed = $events['Indian Grand Prix Athletics'];
        $types = [
            ['winner', 'Gold Medal — 100m Sprint'],
            ['runner_up', 'Silver Medal — 100m Sprint'],
            ['bronze', 'Bronze Medal — 100m Sprint'],
            ['participation', 'Certificate of Participation — 100m Sprint'],
            ['participation', 'Certificate of Participation — 100m Sprint'],
        ];
        $seq = 800;
        foreach ($types as $i => [$type, $title]) {
            Certificate::create([
                'number' => 'ISL-CERT-2026-'.str_pad((string) (++$seq), 5, '0', STR_PAD_LEFT),
                'athlete_id' => $athletes[12 + $i]->id,
                'event_id' => $completed->id,
                'type' => $type,
                'title' => $title,
                'issued_at' => '2026-05-13',
            ]);
        }
    }

    private function seedNews(): void
    {
        $items = [
            ['Indian Sports League 2026 season kicks off across 8 disciplines', 'Season', 'from-saffron-500 to-ink-800', self::IMAGES['Athletics'],
                'The 2026 season opens with a record 4,800 registered athletes competing across boxing, kusti, kabaddi and five more disciplines.'],
            ['Online athlete registration crosses 4,800 entries', 'Registration', 'from-ink-700 to-ink-950', self::IMAGES['Football'],
                'Digital registration with instant unique-ID generation has streamlined onboarding for athletes from all 28 states and 8 union territories.'],
            ['Haryana tops the medal tally after the athletics leg', 'Results', 'from-victory-600 to-ink-900', self::IMAGES['Athletics'],
                'A dominant sprint and field performance puts Haryana on top with 14 gold, ahead of Punjab and Manipur.'],
            ['Live brackets go digital for the National Boxing Championship', 'Technology', 'from-ink-700 to-ink-950', self::IMAGES['Boxing'],
                'Fans can now follow every bout in real time as knockout brackets update live from the ring in Nahan.'],
            ['Akharas to the arena: Bharat Kesari Kushti Dangal returns', 'Kusti', 'from-ink-700 to-ink-950', self::IMAGES['Kusti'],
                'Pehlwans from the akharas of Haryana, Punjab and Uttar Pradesh converge on Sonipat for the traditional clay-pit dangal, now part of the national league calendar.'],
            ['Kabaddi National Series to feature 12 state franchises', 'Kabaddi', 'from-victory-600 to-ink-900', self::IMAGES['Kabaddi'],
                'The Pro Kabaddi National Series returns to Patna with twelve franchises battling over ten days of raids and tackles.'],
        ];

        $day = Carbon::create(2026, 6, 1);
        foreach ($items as $i => [$title, $cat, $gradient, $image, $excerpt]) {
            News::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'category' => $cat,
                'gradient' => $gradient,
                'image' => $image,
                'excerpt' => $excerpt,
                'body' => $excerpt."\n\n".'The Indian Sports League continues to professionalise grassroots competition with a unified digital platform for registration, scheduling, live results and certification. Organisers say the 2026 season represents the largest multi-sport effort yet, with national federations, the Sports Authority of India and state associations collaborating on a single calendar.',
                'published_at' => $day->copy()->addDays($i * 2),
            ]);
        }
    }
}
