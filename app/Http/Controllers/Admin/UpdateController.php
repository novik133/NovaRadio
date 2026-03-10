<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UpdateService;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __construct(private UpdateService $updateService) {}

    public function index()
    {
        $currentVersion = $this->updateService->getCurrentVersion();
        $history = $this->updateService->getUpdateHistory(10);
        $latestCheck = $history->where('type', 'check')->first();

        return view('admin.updates.index', compact('currentVersion', 'history', 'latestCheck'));
    }

    public function check()
    {
        $result = $this->updateService->checkForUpdates();

        if ($result['success'] && ($result['has_update'] ?? false)) {
            return redirect()->route('admin.updates.index')
                ->with('update_available', true)
                ->with('version', $result['latest_version'])
                ->with('changelog', $result['changelog'])
                ->with('download_url', $result['download_url'])
                ->with('html_url', $result['html_url']);
        }

        return redirect()->route('admin.updates.index')
            ->with('success', $result['success'] ? __('admin.updates.check_success') : __('admin.updates.check_failed'));
    }

    public function install(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string',
            'download_url' => 'required|url',
        ]);

        $result = $this->updateService->installUpdate($validated['version'], $validated['download_url']);

        if ($result['success']) {
            return redirect()->route('admin.updates.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.updates.index')
            ->with('error', $result['message']);
    }
}
