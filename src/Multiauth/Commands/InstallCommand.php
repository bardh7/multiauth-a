<?php

namespace Autoluminescent\Multiauth\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hello from Auth';

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
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'multiauth-config',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'multiauth-views',
            '--force' => true,
        ]);
    }
}
