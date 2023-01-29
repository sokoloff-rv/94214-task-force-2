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

    public function __construct(string $fileName, array $columns)
    {
        $this->fileName = $fileName;
        $this->columns = $columns;
    }

    public function import(): void
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

        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }
    }

    public function getData(): array
    {
        return $this->result;
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

    public function generateSqlFile(string $tableName): void
    {
        $this->tableName = $tableName;
        $file = fopen("../$tableName.sql", "w");

        $columnNames = '';
        foreach($this->getHeaderData() as $header) {
            $columnNames .= "`$header`, ";
        }
        $columnNames = rtrim($columnNames, ", ");

        foreach($this->getData() as $line) {
            $lineValues = '';
            foreach($line as $value) {
                $lineValues .= "\"$value\", ";
            }
            $lineValues = rtrim($lineValues, ", ");
            fputs($file, "INSERT INTO $tableName ($columnNames) VALUES ($lineValues);" . PHP_EOL);
        }

        fclose($file);
    }

}
