<?php

namespace App\Console\Commands;

use App\Jobs\UpdateProductLabels;
use Illuminate\Console\Command;

class RunUpdateProductLabels extends Command
{
    protected $signature = 'update:product-labels';
    protected $description = 'Update all product labels';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        UpdateProductLabels::dispatch();
        $this->info('Product labels update job dispatched.');
    }
}
