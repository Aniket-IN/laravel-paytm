<?php

namespace AniketIN\Paytm;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use AniketIN\Paytm\Commands\PaytmCommand;

class PaytmServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-paytm')
            ->hasConfigFile()
            ->hasViews()
            // ->hasMigration('create_laravel-paytm_table')
            // ->hasCommand(PaytmCommand::class)
            ;
    }
}
