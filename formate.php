<?php
$handle = opendir('storage');
if (false === $handle) {
    exit('Can not open "storage".');
}
if (is_file('list.csv')) {
    unlink('list.csv');
}
$totalNumber = 0;
while ($number = readdir($handle)) {
    if (is_dir("storage/$number") && !in_array($number, ['..', '.'])) {
        $fileFloderHandle = opendir("storage/$number");
        if (false === $fileFloderHandle) {
            exit('Open dir "'."storage/$number".'" fail.');
        }
        echo "IN storage/$number" . PHP_EOL;
        while ($csv = readdir($fileFloderHandle)) {
            if (is_file("storage/$number/$csv") && !in_array($csv, ['..', '.'])) {
                echo "READ storage/$number/$csv" . PHP_EOL;
                $tbLinks = explode(PHP_EOL, file_get_contents("storage/$number/$csv"));
                foreach ($tbLinks as $link) {
                    if (!empty($link)) {
                        $encode = trim(str_replace('http://tieba.baidu.com/f?kw=', '', $link));
                        $tbName = urldecode($encode);
                        file_put_contents('list.csv', "\"$tbName\"" . PHP_EOL, FILE_APPEND);
                        $totalNumber++;
                    }
                }
            }
        }
        closedir($fileFloderHandle);
    }
}
closedir($handle);
echo "TOTAL $totalNumber" . PHP_EOL;
