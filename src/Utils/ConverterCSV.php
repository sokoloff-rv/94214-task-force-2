<?php
namespace Taskforce\Utils;

use SplFileObject;
use Taskforce\Exceptions\ExceptionFileFormat;
use Taskforce\Exceptions\ExceptionSourceFile;

class ConverterCSV
{
    private string $fileName;
    private array $columns;
    private object $fileObject;
    private ?string $error = null;

    public function __construct(string $fileName, array $columns)
    {
        $this->fileName = $fileName;
        $this->columns = $columns;
    }

    public function generateSqlFile(string $tableName): void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new ExceptionFileFormat("Заданы неверные заголовки столбцов!");
        }

        if (!file_exists($this->fileName)) {
            throw new ExceptionSourceFile("Выбранный файл не существует!");
        }

        try {
            $this->fileObject = new SplFileObject($this->fileName);
        }
        catch (RuntimeException $exception) {
            throw new ExceptionSourceFile("Не удалось открыть файл на чтение!");
        }

        $header_data = $this->getHeaderData();

        if ($header_data !== $this->columns) {
            throw new ExceptionFileFormat("Исходный файл не содержит необходимых столбцов!");
        }

        $this->tableName = $tableName;
        $file = fopen("../$tableName.sql", "w");

        $columnNames = '';
        foreach($this->getHeaderData() as $header) {
            $columnNames .= "`$header`, ";
        }
        $columnNames = rtrim($columnNames, ", ");

        foreach ($this->getNextLine() as $line) {
            $lineValues = '';
            foreach($line as $value) {
                $lineValues .= "\"$value\", ";
            }
            $lineValues = rtrim($lineValues, ", ");
            fputs($file, "INSERT INTO $tableName ($columnNames) VALUES ($lineValues);" . PHP_EOL);
        }

        fclose($file);
    }

    private function getHeaderData(): ?array
    {
        $this->fileObject->rewind();

        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    private function validateColumns(array $columns): bool
    {
        $result = true;

        if (count($columns)) {
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
}
