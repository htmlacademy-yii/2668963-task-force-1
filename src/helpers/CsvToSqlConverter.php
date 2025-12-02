<?php

declare(strict_types=1);
namespace HtmlAcademy\helpers;

use Exception;
use SplFileObject;

class CsvToSqlConverter
{
    public static function convert(string $sourceFilePath, string $outDirectoryPath): void
    {
        $fileObject = new SplFileObject($sourceFilePath);
        $fileInfo = $fileObject->getFileInfo();
        $tableName = $fileInfo->getBasename('.' . $fileInfo->getExtension());

        $sql = "INSERT INTO {$tableName}";
        $values = [];
        
        do{
            $row = $fileObject->fgetcsv();

            if ($fileObject->key() === 0) {
                $sql .= ' (' . implode(',', $row) . ') VALUES' . PHP_EOL;

                continue;
            }
            
            $values[] = "\t(" . implode(
                ',', 
                array_map(
                    callback: function (string $value) {
                        return "'{$value}'";
                    },
                    array: $row
                )
            ) . ")";
        } while (!$fileObject->eof());

        $sql .= implode(',' . PHP_EOL, $values) . PHP_EOL . ';';

        if (!file_put_contents("$outDirectoryPath/$tableName.sql", $sql)) {
            throw new Exception(message: 'Ошибка записи');
        };
    }
}