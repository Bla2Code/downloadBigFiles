<?php

/**
 * Демонстрация скачивания файла с возможностью докачки.
 * https://backender.ru/
 */
class DownloadBackender
{
    public $this->filePath;
    public function Main() {
        // Файл, который скачиваем
        $this->filePath = __DIR__ . '/100mb.bin';

        if (file_exists($this->filePath) == false)
        {
            die("Файл не существует");
        }

        // открываем файл на чтение в бинарном формате
        $fileDescription = fopen($this->filePath, 'rb');

        // если серверу был передан HTTP_RANGE, то мы должны пропустить это количество байт
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = (int)str_replace('bytes=', '', $_SERVER['HTTP_RANGE']);
            if ($range > 0) {
                fseek($fileDescription, $range);
                header($_SERVER['SERVER_PROTOCOL'] . ' 206 Partial Content');
            }
        }

        // очищаем буфер
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        // добавляем необходимые заголовки
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($this->filePath));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($this->filePath));

        // читаем файл
        while (!feof($fileDescription)) {
            print fread($fileDescription, 8192);
        }
        fclose($fileDescription);
        exit;
    }
}
