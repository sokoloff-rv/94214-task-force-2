<?php

namespace Taskforce\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Geocoder
{
    /**
     * Определяет координаты по заданному адресу, используя сервисы картографии.
     *
     * @param string $location Адрес для определения координат.
     * @return array Массив, содержащий координаты в формате [долгота, широта].
     */
    public static function determineCoordinates(string $location): array
    {
        $apiKey = 'e666f398-c983-4bde-8f14-e3fec900592a';

        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/',
        ]);

        try {
            $response = $client->request('GET', '1.x', [
                'query' => ['geocode' => $location, 'apikey' => $apiKey, 'format' => 'json']
            ]);
        } catch (GuzzleException $error) {
            throw new \RuntimeException('Ошибка при запросе к API: ' . $error->getMessage());
        }

        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Ошибка при парсинге ответа от API: ' . json_last_error_msg());
        }

        $coordinates = $responseData['response']['GeoObjectCollection']['featureMember']['0']['GeoObject']['Point']['pos'];

        return explode(' ', $coordinates);
    }
}
