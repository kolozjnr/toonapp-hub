<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\User;
use App\Services\OrderStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\Finder\SplFileInfo;

class DashboardController extends Controller
{
    /**
     * How long the public disk usage is remembered for, so a folder of covers
     * and gallery images is not walked on every page load.
     */
    private const STORAGE_CACHE_MINUTES = 10;

    /**
     * Show the admin dashboard.
     */
    public function show(OrderStats $stats): View
    {
        $summary = $stats->summary();

        return view('admin.dashboard', [
            'summary' => $summary,
            'latestOrders' => $stats->latest(),
            'salesByPlugin' => $stats->salesByPlugin($summary['currency']),
            'dailyOrders' => $stats->dailyOrders(),
            'pluginCount' => Plugin::query()->count(),
            'customerCount' => $this->customerCount(),
            'storage' => $this->storageUsage(),
        ]);
    }

    /**
     * The shoppers who can buy from the store: every registered account that
     * is not one of the staff logins.
     */
    private function customerCount(): int
    {
        return User::query()
            ->whereDoesntHave('roles', fn (Builder $query): Builder => $query->whereIn('name', ['admin', 'superadmin']))
            ->count();
    }

    /**
     * How much of the public disk the shop's files take up, and how far that
     * is into the configured quota.
     *
     * @return array{
     *     used: int,
     *     files: int,
     *     percent: int,
     *     used_label: string,
     *     quota_label: string,
     *     width: int
     * }
     */
    private function storageUsage(): array
    {
        $usage = Cache::remember(
            'admin.storage',
            now()->addMinutes(self::STORAGE_CACHE_MINUTES),
            fn (): array => $this->publicDiskUsage()
        );

        $quota = max(1, (int) config('filesystems.quota'));
        $percent = min(100, (int) round($usage['used'] / $quota * 100));

        return $usage + [
            'percent' => $percent,
            'used_label' => $this->bytes($usage['used']),
            'quota_label' => $this->bytes($quota),
            'width' => $percent > 0 ? max(5, (int) round($percent / 5) * 5) : 0,
        ];
    }

    /**
     * Walk the public disk, where plugin covers and gallery images live.
     *
     * @return array{used: int, files: int}
     */
    private function publicDiskUsage(): array
    {
        $root = (string) config('filesystems.disks.public.root');

        if ($root === '' || ! is_dir($root)) {
            return ['used' => 0, 'files' => 0];
        }

        $files = File::allFiles($root);

        return [
            'used' => array_sum(array_map(fn (SplFileInfo $file): int => $file->getSize(), $files)),
            'files' => count($files),
        ];
    }

    /**
     * A byte count in the largest unit that keeps it readable, e.g. "12.4 MB".
     */
    private function bytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $value = (float) $bytes;

        for ($i = 0; $value >= 1024 && $i < count($units) - 1; $i++) {
            $value /= 1024;
        }

        return $i === 0 ? (string) (int) $value.' '.$units[$i] : round($value, 1).' '.$units[$i];
    }
}
