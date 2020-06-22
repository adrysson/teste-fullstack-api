<?php


namespace App\Support\PathGenerator;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class CustomPathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return $media->model->name . ' ' . $media->getKey();
    }
}
