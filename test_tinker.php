$user = App\Models\User::first();
Auth::login($user);

Illuminate\Support\Facades\DB::listen(function ($query) {
    echo "[{$query->time}ms] {$query->sql}\n";
});

$start = microtime(true);
$res = app('App\Http\Controllers\DashboardController')->wadir();
echo "Total Time: " . (microtime(true) - $start) . " seconds\n";
