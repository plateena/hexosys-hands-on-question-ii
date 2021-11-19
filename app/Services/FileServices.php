<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileServices
{

    public $imageBinary;
    public $fileName;

    protected $prefix = 'fim_';

    /**
     * @param $dependencies
     */
    public function __construct($imageBinary)
    {
        $this->imageBinary = $imageBinary;
        $this->genFileName();
    }

    function save()
    {
        Storage::disk('public')->put($this->fileName, $this->imageBinary);
    }

    public function genFileName()
    {
        $ext = $this->getFileExt($this->imageBinary);
        $this->fileName = $this->prefix . $this->genSaltString() . '.' . $ext;
    }

    public function genSaltString()
    {
        return md5(Str::random(10) . microtime());
    }

    public function getFileExt()
    {
        $fh = fopen('php://memory', 'w+b');
        fwrite($fh, $this->imageBinary);
        $contentType = mime_content_type($fh);
        fclose($fh);

        $ext  = (explode('/', $contentType))[1];
        return $ext;
    }
}
