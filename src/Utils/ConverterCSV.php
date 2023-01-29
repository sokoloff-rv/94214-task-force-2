<?php
namespace Taskforce\Utils;

use SplFileObject;
use Taskforce\Exceptions\ExceptionFileFormat;
use Taskforce\Exceptions\ExceptionSourceFile;

class ConverterCSV
{
    private $fileName;
    private $columns;
    private $fileObject;
    private $result = [];
    private $error = null;

    /* конструктор, присваивает значение переменным */
    public function __construct(string $fileName, array $columns)
    {
        $this->fileName = $fileName;
        $this->columns = $columns;
    }

    /* выполняет запись данных из CSV файла в массив */
    public function import(): void
    {
        /* выводит сообщение об ошибке, если заданы неверные заголовки столбцов */
        if (!$this->validateColumns($this->columns)) {
            throw new ExceptionFileFormat("Заданы неверные заголовки столбцов!");
        }

        /* выводит сообщение об ошибке, если заданный файл не существует */
        if (!file_exists($this->fileName)) {
            throw new ExceptionSourceFile("Выбранный файл не существует!");
        }

        /* создает объект файла */
        try {
            $this->fileObject = new SplFileObject($this->fileName);
        }
        /* выводит сообщение об ошибке, если не удалось прочитать файл */ catch (RuntimeException $exception) {
            throw new ExceptionSourceFile("Не удалось открыть файл на чтение!");
        }

        /* запись заголовков файла в переменную */
        $header_data = $this->getHeaderData();

        /* проверка наличия в файле переданных столбцов */
        if ($header_data !== $this->columns) {
            throw new ExceptionFileFormat("Исходный файл не содержит необходимых столбцов!");
        }

        /* построчная запись данных из файла в массив */
        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }
    }

    /* выводит массив с результатами (если уже был выполнен импорт) */
    public function getData(): array
    {
        return $this->result;
    }

    /* получение заголовков файла */
    private function getHeaderData(): ?array
    {

        /* перемотка в начало файла */
        $this->fileObject->rewind();

        /* получение строки файла и её интерпретация как CSV */
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /* получение следующей строки */
    private function getNextLine(): ?iterable
    {
        $result = null;

        /* получение следующей строки пока файл не закончится */
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    /* проверяет корректность переданных заголовков столбцов */
    private function validateColumns(array $columns): bool
    {
        $result = true;

        /* проверяем есть ли заголовоки вообще */
        if (count($columns)) {

            /* проверяем являются ли они строками */
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }

    /* генерирует файл SQL */
    public function generateSqlFile(string $tableName): void
    {
        $this->tableName = $tableName;
        $file = fopen("../$tableName.sql", "w");

        $columnNames = '';
        foreach($this->getHeaderData() as $header) {
            $columnNames .= "'$header', ";
        }
        $columnNames = rtrim($columnNames, ", ");

        foreach($this->getData() as $line) {
            $lineValues = '';
            foreach($line as $value) {
                $lineValues .= "'$value', ";
            }
            $lineValues = rtrim($lineValues, ", ");
            fputs($file, "INSERT INTO categories ($columnNames) VALUES ($lineValues);" . PHP_EOL);
        }

        fclose($file);
    }

}
