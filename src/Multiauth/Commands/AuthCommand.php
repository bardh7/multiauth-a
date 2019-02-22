<?php

namespace Autoluminescent\Multiauth\Commands;

use Illuminate\Console\Command;

class AuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:hello';

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
 		$this->alert("Hello from Auth.");
    }
}
