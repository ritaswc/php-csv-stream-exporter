<?php

namespace Ritaswc\CsvStream;

class Exporter
{
    protected $filename          = '';
    protected $outputLocation    = '';
    protected $withUTF8Bom       = true;
    protected $fd                = null;
    protected $formatLongNumeric = true;

    const UTF8_BOM = "\xEF\xBB\xBF";

    public function __construct(
        $filename,
        $withUTF8Bom = true,
        $outputLocation = 'php://output',
        $max_execution_time = 100000,
        $formatLongNumeric = true
    )
    {
        $this->filename          = $filename;
        $this->withUTF8Bom       = $withUTF8Bom;
        $this->outputLocation    = $outputLocation;
        $this->formatLongNumeric = $formatLongNumeric;
        header('content-type:application/octet-stream');
        if (count(explode('.', $this->filename)) < 2) {
            $this->filename .= '.csv';
        }
        header("Content-Disposition: attachment; filename=" . urlencode($this->filename));
        // UTF8的BOM头
        $this->fd = fopen($this->outputLocation, 'w+');
        if ($this->withUTF8Bom) {
            fwrite($this->fd, static::UTF8_BOM);
        }
        @ini_set('max_execution_time', $max_execution_time);
    }

    public function writeHeaders($headers)
    {
        return $this->writeLine($headers);
    }

    public function writeLine($line)
    {
        if ($this->formatLongNumeric) {
            $formattedLine = [];
            foreach ($line as $v) {
                if (is_numeric($v) && mb_strlen($v) > 8) {
                    $formattedLine[] = "\t" . $v;
                } else {
                    $formattedLine[] = $v;
                }
            }
            return fputcsv($this->fd, $formattedLine);
        }
        return fputcsv($this->fd, $line);
    }

    public function __destruct()
    {
        if (is_resource($this->fd)) {
            fclose($this->fd);
        }
        die;
    }
}