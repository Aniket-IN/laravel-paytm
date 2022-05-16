<?php

namespace AniketIN\Paytm\Commands;

use Illuminate\Console\Command;

class PaytmCommand extends Command
{
    public $signature = 'laravel-paytm';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
