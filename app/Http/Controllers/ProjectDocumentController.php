<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDocumentStoreRequest;
use App\Models\ProjectDocument;

class ProjectDocumentController extends Controller
{
    public function store(ProjectDocumentStoreRequest $request)
    {
        $validated = $request->validated();
        $projectDocument = new ProjectDocument($validated);
        // save the document first to get an ID
        $projectDocument->save();

        // store the file
        $file = $validated['file'];
        $projectDocument->storeFile($file);

        // save it again after updating the file to capture the file info
        $projectDocument->save();

        return redirect()->back();
    }

    public function destroy(ProjectDocument $projectDocument)
    {
        $projectDocument->delete();

        return back();
    }
}
