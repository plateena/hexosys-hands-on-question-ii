<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * FileServices
 *
 * @copyright 2021 plateena
 * @author plateena <plateena711@gmail.com>
 */
class FileServices
{

    /**
     * imageBinary 
     *
     * file binary
     *
     * @var mixed
     * @access public
     */
    public $imageBinary;

    /**
     * fileName 
     *
     * Name of stored file
     *
     * @var mixed
     * @access public
     */
    public $fileName;

    /**
     * prefix 
     *
     * file prefix name
     *
     * @var string
     * @access protected
     */
    protected $prefix = 'fim_';

    /**
     * @param $dependencies
     */
    public function __construct($imageBinary)
    {
        $this->imageBinary = $imageBinary;
        $this->genFileName();
    }

    /**
     * save
     *
     * store the file to public folder
     *
     * @access public
     * @return void
     */
    public function save()
    {
        return Storage::disk('public')->put($this->fileName, $this->imageBinary);
    } // End function save

    /**
     * genFileName
     *
     * generate file name to be stored
     *
     * @access public
     * @return string file name
     */
    public function genFileName(): string
    {
        $ext = $this->getFileExt($this->imageBinary);
        return $this->fileName = $this->prefix . $this->genSaltString() . '.' . $ext;
    } // End function genFileName

    /**
     * genSaltString
     *
     * generate unique salt string to put with file name
     *
     * @access public
     * @return string the salt
     */
    public function genSaltString(): string
    {
        return md5(Str::random(10) . microtime()); // NOSONAR
    } // End function genSaltString

    /**
     * getFileExt
     *
     * get the original file ext from the binary file
     *
     * @access public
     * @return string the extension
     */
    public function getFileExt(): string
    {
        $fh = fopen('php://memory', 'w+b');
        fwrite($fh, $this->imageBinary);
        $contentType = mime_content_type($fh);
        fclose($fh);

        return (explode('/', $contentType))[1];
    } // End function getFileExt
} // End class FileServices
