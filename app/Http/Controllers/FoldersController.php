<?php

namespace App\Http\Controllers;

use App\Enums\SortingMethod;
use App\Enums\SortingOrder;
use App\Facades\LogViewer;
use App\Http\Resources\LogFolderResource;
use App\LogFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class FoldersController
{
    public function index(Request $request)
    {
        $folders = LogViewer::getFilesGroupedByFolder();

        $sortingMethod = config('log-viewer.defaults.folder_sorting_method', SortingMethod::ModifiedTime);
        $sortingOrder = config('log-viewer.defaults.folder_sorting_order', SortingOrder::Descending);

        $fileSortingMethod = config('log-viewer.defaults.file_sorting_method', SortingMethod::ModifiedTime);
        $fileSortingOrder = $this->validateDirection($request->query('direction'));

        $folders->sortUsing($sortingMethod, $sortingOrder);

        $folders->each(fn ($folder) => $folder->files()->sortUsing($fileSortingMethod, $fileSortingOrder));

        return LogFolderResource::collection($folders->values());
    }

    private function validateDirection(?string $direction): string
    {
        if ($direction === SortingOrder::Ascending) {
            return SortingOrder::Ascending;
        }

        return SortingOrder::Descending;
    }

    public function requestDownload(Request $request, string $folderIdentifier)
    {
        $folder = LogViewer::getFolder($folderIdentifier);

        abort_if(is_null($folder), 404);

        Gate::authorize('downloadLogFolder', $folder);

        return response()->json([
            'url' => URL::temporarySignedRoute(
                'log-viewer.folders.download',
                now()->addMinutes(30),   // longer time to allow for processing of the ZIP file
                ['folderIdentifier' => $folderIdentifier]
            ),
        ]);
    }

    public function download(string $folderIdentifier)
    {
        $folder = LogViewer::getFolder($folderIdentifier);

        return $folder->download();
    }

    public function clearCache(string $folderIdentifier)
    {
        $folder = LogViewer::getFolder($folderIdentifier);

        abort_if(is_null($folder), 404);

        $folder?->files()->each->clearCache();

        return response()->json(['success' => true]);
    }

    public function delete(string $folderIdentifier)
    {
        $folder = LogViewer::getFolder($folderIdentifier);

        if (is_null($folder)) {
            return response()->json(['success' => true]);
        }

        Gate::authorize('deleteLogFolder', $folder);

        $folder->files()->each(function (LogFile $file) {
            if (Gate::check('deleteLogFile', $file)) {
                $file->delete();
            }
        });

        return response()->json(['success' => true]);
    }
}
