<?php

if (!function_exists('storage_url')) {
    /**
     * Helper function to generate proper storage URL
     * Usage: storage_url($file->lampiran)
     */
    function storage_url($path)
    {
        return \App\Helpers\AppHelper::instance()->storageUrl($path);
    }
}

