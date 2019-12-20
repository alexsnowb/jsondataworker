<?php


namespace Snowb\DataWorker;


/**
 * Class DataWorker
 * @package Snowb\DataWorker
 */
class DataWorker
{

    /** @var string  */
    private $uploadDir;
    /** @var string */
    private $downloadDir;
    /** @var \Upload\File */
    private $uploadedFile;
    /**
     * @var array
     */
    private $output;

    /**
     * DataWorker constructor.
     * @param string $uploadDir
     */
    public function __construct($uploadDir, $downloadDir)
    {
        if (empty($uploadDir))
            throw new \InvalidArgumentException('Upload directory not configured');
        if (empty($uploadDir))
            throw new \InvalidArgumentException('Download directory not configured');
        $this->uploadDir = $uploadDir;
        $this->downloadDir = $downloadDir;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        if (!empty($_POST) || !empty($_FILES)) return true;

        return false;
    }

    public function uploadFile($key = 'file')
    {
        $storage = new \Upload\Storage\FileSystem($this->uploadDir);
        $file = new \Upload\File($key, $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations(array(
            // Ensure file is of type "text/csv"
            new \Upload\Validation\Mimetype(['text/csv', 'text/plain']),

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('5M')
        ));

        // Try to upload file
        try {
            // Success!
            $file->upload();
            $this->uploadedFile = $file;
        } catch (\Exception $e) {
            // Fail!
            var_dump($file->getErrors());
        }

        return true;
    }

    public function convertFile()
    {
        $dataTxt = array_map('str_getcsv', file($this->uploadDir . DIRECTORY_SEPARATOR .$this->uploadedFile->getNameWithExtension()));
        $dataJson = new Data();
        $dataModel = json_decode($dataJson->dataJsonTemplate);

        foreach ($dataTxt as $row) {
            $dataRowJson = str_replace('#####DATACOLUMN1#####', $row[0], $dataJson->itemJsonTemplate);
            $dataRowJson = str_replace('#####DATACOLUMN2#####', $row[1], $dataRowJson);
            $dataRowModel = json_decode($dataRowJson);
            foreach ($dataRowModel as $item) {
                $dataModel->action[] = $item;
            }
         }

        $this->output = $dataModel;

    }

    public function outputData()
    {
        header('Content-Type: application/json');
        echo json_encode($this->output,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }




}