<?php

namespace Taskforce\Utils;

use SplFileObject;
use Taskforce\Exceptions\ExceptionFileFormat;
use Taskforce\Exceptions\ExceptionSourceFile;

/**
 * Класс ConverterCSV отвечает за конвертирование CSV файла в SQL файл.
 */
class ConverterCSV
{
    private string $fileName;
    private array $columns;
    private SplFileObject $fileObject;
    private ?string $error = null;

    /**
     * Конструктор класса ConverterCSV.
     *
     * @param string $fileName Имя файла CSV.
     * @param array $columns Массив с названиями колонок.
     */
    public function __construct(string $fileName, array $columns)
    {
        $this->fileName = $fileName;
        $this->columns = $columns;
    }

    /**
     * Генерирует SQL файл на основе данных из CSV файла.
     *
     * @param string $tableName Имя таблицы в базе данных.
     * @throws ExceptionFileFormat Если заданы неверные заголовки столбцов.
     * @throws ExceptionSourceFile Если файл не существует или не удалось открыть файл на чтение.
     */
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
        catch (\RuntimeException $exception) {
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

    /**
     * Получает заголовки столбцов из файла CSV.
     *
     * @return ?array Массив с заголовками столбцов.
     */
    private function getHeaderData(): ?array
    {
        $this->fileObject->rewind();

        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * Возвращает итератор, который перебирает строки CSV файла.
     *
     * @return ?iterable Итератор для перебора строк.
     */
    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    /**
     * Проверяет, что массив с колонками имеет правильный формат.
     *
     * @param array $columns Массив с названиями колонок.
     * @return bool Возвращает true, если колонки имеют правильный формат, иначе false.
     */
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
