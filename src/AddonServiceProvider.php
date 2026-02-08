<?php

namespace Lagdo\Backpack\Dbadmin;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'lagdo';
    protected $packageName = 'dbadmin-backpack-addon';
    protected $commands = [];
}
