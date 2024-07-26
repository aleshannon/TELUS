<?php
namespace App\Service;

class DataTransformer
{
    public function jsonToCsv(string $jsonFilePath, string $csvFilePath)
    {
        if (!file_exists($jsonFilePath)) {
            throw new \Exception("JSON file not found: " . $jsonFilePath);
        }

        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Acceder a los datos anidados en la clave 'users'
        $userData = $jsonData['users'] ?? null;

        if (empty($userData)) {
            throw new \Exception("JSON data is empty or invalid");
        }

        $csvFile = fopen($csvFilePath, 'w');

        // Escribir encabezados
        fputcsv($csvFile, array_keys($this->flattenArray($userData[0])));

        // Escribir datos
        foreach ($userData as $row) {
            fputcsv($csvFile, $this->flattenArray($row));
        }

        fclose($csvFile);
    }

    public function createSummary(string $jsonFilePath, string $summaryFilePath)
    {
        if (!file_exists($jsonFilePath)) {
            throw new \Exception("JSON file not found: " . $jsonFilePath);
        }

        $jsonData = json_decode(file_get_contents($jsonFilePath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Acceder a los datos anidados en la clave 'users'
        $userData = $jsonData['users'] ?? null;

        if (empty($userData)) {
            throw new \Exception("JSON data is empty or invalid");
        }

        $summaryData = [];

        // Resumir datos (ejemplo: contar usuarios)
        $summaryData['total_users'] = count($userData);

        $csvFile = fopen($summaryFilePath, 'w');
        fputcsv($csvFile, array_keys($summaryData));
        fputcsv($csvFile, $summaryData);

        fclose($csvFile);
    }

    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}_{$key}" : $key;
            if (is_array($value)) {
                $result += $this->flattenArray($value, $newKey);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
