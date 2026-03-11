// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->command('inventory:check-low-stock')->hourly();
}