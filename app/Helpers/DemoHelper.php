<?php

namespace app\Helpers;

use App\Models\Setting;

/**
 * DemoHelper — handles demo mode logic and static data loading
 */
class DemoHelper extends Helper
{
    protected array $demoData = [];

    public function __construct()
    {
        if ($this->isDemoMode()) {
            $this->loadDemoJson();
        }
    }

    public function isDemoMode(): bool
    {
        return (bool) Setting::get('demo_mode', false);
    }

    protected function loadDemoJson(): void
    {
        $path = public_path('/demo/demo-data.json');

        if (!file_exists($path))
            return;

        $json = file_get_contents($path);
        $this->demoData = json_decode($json, true) ?? [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->demoData[$key] ?? $default;
    }

    public function preventIfDemo(string $message = 'This action is disabled in Demo Mode.'): void
    {
        if ($this->isDemoMode()) {
            abort(403, $message);
        }
    }
}
