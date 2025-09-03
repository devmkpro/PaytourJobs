<?php

namespace App\Console\Commands;

use App\Models\Candidates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateResumesToPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resumes:migrate-to-public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate resume files from private to public storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of resume files to public storage...');
        
        $candidates = Candidates::whereNotNull('resume_path')->get();
        $migrated = 0;
        $errors = 0;
        
        foreach ($candidates as $candidate) {
            $oldPath = $candidate->resume_path;
            
            // Check if file exists in private storage
            if (Storage::disk('local')->exists($oldPath)) {
                try {
                    // Copy file to public storage
                    $fileContent = Storage::disk('local')->get($oldPath);
                    Storage::disk('public')->put($oldPath, $fileContent);
                    
                    $this->info("Migrated: {$oldPath}");
                    $migrated++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate {$oldPath}: " . $e->getMessage());
                    $errors++;
                }
            } else {
                $this->warn("File not found in private storage: {$oldPath}");
            }
        }
        
        $this->info("\nMigration completed!");
        $this->info("Files migrated: {$migrated}");
        $this->info("Errors: {$errors}");
        
        return Command::SUCCESS;
    }
}
