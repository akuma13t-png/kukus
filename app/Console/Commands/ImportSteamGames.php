<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class ImportSteamGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steam:import {--limit=20 : The number of games to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import popular games from Steam API';

    /**
     * List of Steam App IDs to import.
     * Includes Top Sellers, Most Played, and Classics.
     */
    protected $appIds = [
        // --- MULTIPLAYER / ESPORTS ---
        730,     // Counter-Strike 2
        570,     // Dota 2
        578080,  // PUBG: BATTLEGROUNDS
        1172470, // Apex Legends
        2357570, // Overwatch 2
        1938090, // Call of Duty®
        359550,  // Tom Clancy's Rainbow Six Siege
        440,     // Team Fortress 2
        252490,  // Rust
        230410,  // Warframe
        1085660, // Destiny 2
        240720,  // NARAKA: BLADEPOINT
        381210,  // Dead by Daylight
        218620,  // PAYDAY 2
        252950,  // Rocket League (Legacy support if API still works)
        4000,    // Garry's Mod
        550,     // Left 4 Dead 2
        945360,  // Among Us
        1966720, // Lethal Company
        739630,  // Phasmophobia
        2881650, // Content Warning
        553850,  // HELLDIVERS™ 2
        
        // --- OPEN WORLD / RPG ---
        271590,  // Grand Theft Auto V
        1091500, // Cyberpunk 2077
        1245620, // ELDEN RING
        1086940, // Baldur's Gate 3
        1174180, // Red Dead Redemption 2
        292030,  // The Witcher 3: Wild Hunt
        489830,  // The Elder Scrolls V: Skyrim Special Edition
        377160,  // Fallout 4
        1151340, // Fallout 76
        2054970, // Dragon's Dogma 2
        1593500, // God of War
        1817070, // Marvel’s Spider-Man Remastered
        1151640, // Horizon Zero Dawn™ Complete Edition
        1888930, // The Last of Us™ Part I
        2050650, // Resident Evil 4
        1196590, // Resident Evil Village
        413080,  // Resident Evil 7 Biohazard
        814380,  // Sekiro™: Shadows Die Twice
        374320,  // DARK SOULS™ III
        
        // --- SURVIVAL / CRAFTING ---
        413150,  // Stardew Valley
        105600,  // Terraria
        1623730, // Palworld
        892970,  // Valheim
        648800,  // Raft
        264710,  // Subnautica
        108600,  // Project Zomboid
        221100,  // DayZ
        346110,  // ARK: Survival Evolved
        2399830, // ARK: Survival Ascended
        251570,  // 7 Days to Die
        
        // --- STRATEGY / SIMULATION ---
        289070,  // Sid Meier’s Civilization® VI
        281990,  // Stellaris
        394360,  // Hearts of Iron IV
        255710,  // Cities: Skylines
        949230,  // Cities: Skylines II
        1363080, // Manor Lords
        227300,  // Euro Truck Simulator 2
        270880,  // American Truck Simulator
        427520,  // Factorio
        294100,  // RimWorld
        233860,  // Kenshi
        1142710, // Total War: WARHAMMER III
        
        // --- INDIE / ROGUELIKE ---
        367520,  // Hollow Knight
        1145360, // Hades
        1145350, // Hades II
        646570,  // Slay the Spire
        1794680, // Vampire Survivors
        548430,  // Deep Rock Galactic
        632360,  // Risk of Rain 2
        268910,  // Cuphead
        391540,  // Undertale
        504230,  // Celeste
        632470,  // Disco Elysium - The Final Cut
        753640,  // Outer Wilds
        304430,  // Inside
        262060,  // Darkest Dungeon
        
        // --- FIGHTING ---
        1364780, // Street Fighter™ 6
        1778820, // TEKKEN 8
        1971870, // Mortal Kombat 1
        1384160, // GUILTY GEAR -STRIVE-
        
        // --- SPORTS / RACING ---
        2195250, // EA SPORTS FC™ 24
        2338770, // NBA 2K24
        1551360, // Forza Horizon 5
        1293830, // Forza Horizon 4
        244210,  // Assetto Corsa
        805550,  // Assetto Corsa Competizione
        284160,  // BeamNG.drive
        1080110, // F1® 23
        
        // --- ACTION / ADVENTURE ---
        582010,  // Monster Hunter: World
        1446780, // MONSTER HUNTER RISE
        594650,  // Hunt: Showdown
        2074920, // The First Descendant
        2358720, // Black Myth: Wukong
        1203220, // NARUTO X BORUTO Ultimate Ninja STORM CONNECTIONS
        1326860, // TEVI
        1604030, // V Rising
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $this->info("Starting Steam Game Import (Limit: {$limit})...");

        // Shuffle array to get random variety if limit is small
        // Or keep order if user wants top games? Let's keep order for consistency, 
        // but since the list is grouped by genre, maybe shuffling is better to populate all genres?
        // Let's NOT shuffle to ensure the most popular ones (at top) are always imported first.
        // Actually, the list is manually grouped. Let's just take the first N.
        
        $targetAppIds = array_slice($this->appIds, 0, $limit);

        $bar = $this->output->createProgressBar(count($targetAppIds));
        $bar->start();

        foreach ($targetAppIds as $appId) {
            try {
                $this->importGame($appId);
            } catch (\Exception $e) {
                // Log error but continue
                // $this->error("Failed to import App ID {$appId}: " . $e->getMessage());
                Log::error("Steam Import Error [{$appId}]: " . $e->getMessage());
            }
            $bar->advance();
            // Sleep to avoid rate limiting (Steam allows ~200 requests/5 mins, so 1.5s is safe)
            usleep(1500000); // 1.5 seconds
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import completed successfully!');
    }

    protected function importGame($appId)
    {
        // Fetch details from Steam Store API
        // Parameter 'cc=id' untuk mata uang Rupiah (Indonesia)
        // Disable SSL verification for local dev environment
        $response = Http::withoutVerifying()->get("https://store.steampowered.com/api/appdetails?appids={$appId}&cc=id");

        if ($response->failed()) {
            throw new \Exception("API request failed");
        }

        $data = $response->json();

        if (!isset($data[$appId]['success']) || !$data[$appId]['success']) {
            throw new \Exception("Game data not found or success is false");
        }

        $gameData = $data[$appId]['data'];

        // 1. Title
        $title = $gameData['name'];

        // 2. Description (Short)
        $description = $gameData['short_description'] ?? $gameData['detailed_description'] ?? 'No description available.';

        // 3. Price
        $price = 0;
        $discountPercent = 0;

        if (isset($gameData['is_free']) && $gameData['is_free']) {
            $price = 0;
        } elseif (isset($gameData['price_overview'])) {
            $price = $gameData['price_overview']['initial'] / 100;
            $discountPercent = $gameData['price_overview']['discount_percent'];
        }

        // 4. Genre
        $genre = 'Action'; // Default
        if (isset($gameData['genres']) && count($gameData['genres']) > 0) {
            $genre = $gameData['genres'][0]['description'];
        }

        // 5. Publisher
        $publisher = 'Steam Import';
        if (isset($gameData['publishers']) && count($gameData['publishers']) > 0) {
            $publisher = $gameData['publishers'][0];
        }

        // 6. Release Date
        $releaseDate = now();
        if (isset($gameData['release_date']['date'])) {
            try {
                $releaseDate = \Carbon\Carbon::parse($gameData['release_date']['date']);
            } catch (\Exception $e) {
                // Keep default
            }
        }

        // 7. Cover Image
        $coverImage = $gameData['header_image'] ?? null;

        // 8. Screenshots
        $screenshots = [];
        if (isset($gameData['screenshots'])) {
            foreach ($gameData['screenshots'] as $ss) {
                $screenshots[] = $ss['path_full'];
                if (count($screenshots) >= 5) break; // Limit 5 screenshots
            }
        }

        // 9. Trailer (Movie)
        $trailerUrl = null;
        if (isset($gameData['movies']) && count($gameData['movies']) > 0) {
            $trailerUrl = $gameData['movies'][0]['mp4']['480'] ?? null;
        }

        // Insert or Update
        Game::updateOrCreate(
            ['title' => $title], // Check by title to avoid duplicates
            [
                'description' => $description,
                'price' => $price,
                'genre' => $genre,
                'publisher' => $publisher,
                'release_date' => $releaseDate,
                'cover_image' => $coverImage,
                'screenshots' => $screenshots, // Casted to JSON in model
                'trailer_url' => $trailerUrl,
                'is_approved' => true,
                'is_featured' => false, // Default not featured, admin can change
                'discount_percent' => $discountPercent,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
