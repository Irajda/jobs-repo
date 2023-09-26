<?php

namespace App\Console\Commands;

use App\Helpers\SwaggerDocumentation;
use Illuminate\Console\Command;

class SwgDocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swg:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Swagger documentation.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new SwaggerDocumentation)->makeJson();
        $this->info("done");
    }
}
