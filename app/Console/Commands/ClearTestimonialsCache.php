<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\ViewServiceProvider;

class ClearTestimonialsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-testimonials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear testimonials cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ViewServiceProvider::refreshCache('testimonials');
        
        $this->info('Testimonials cache cleared successfully!');
        
        return 0;
    }
}
