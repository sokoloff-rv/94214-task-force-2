<?php

namespace Taskforce\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Geocoder
{
    /**
     * Получение данных о местоположении по заданному адресу или координатам.
     *
     * @param string $location Адрес для определения координат.
     * @param string $format Формат данных, который необходимо вернуть. Возможные значения:
     *  - 'coordinates' - координаты в формате [долгота, широта];
     *  - 'city' - название города;
     *  - 'address' - адрес объекта;
     *  - 'allData' - все данные в формате [координаты, название города, адрес объекта].
     * @return string|array Массив или строка с данными в зависимости от заданного формата.
     * @throws \RuntimeException Если произошла ошибка при запросе к API или при парсинге ответа от API.
     * @throws \InvalidArgumentException Если задан недопустимый формат данных.
     */
    public static function getLocationData(string $location, string $format = 'allData'): string | array
    {
        $apiKey = 'e666f398-c983-4bde-8f14-e3fec900592a';

        $client = new Client([
            'base_uri' => 'https://geocode-maps.yandex.ru/',
        ]);

        try {
            $response = $client->request('GET', '1.x', [
                'query' => ['geocode' => $location, 'apikey' => $apiKey, 'format' => 'json'],
            ]);
        } catch (GuzzleException $error) {
            throw new \RuntimeException('Ошибка при запросе к API: ' . $error->getMessage());
        }

        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Ошибка при парсинге ответа от API: ' . json_last_error_msg());
        }

        $geoObject = $responseData['response']['GeoObjectCollection']['featureMember']['0']['GeoObject'];

        $coordinates = explode(' ', $geoObject['Point']['pos']);
        $city = explode(' ', $geoObject['description'])[0];
        $address = $geoObject['name'];

        return match($format) {
            'coordinates' => $coordinates,
            'city' => $city,
            'address' => $address,
            'allData' => [$coordinates, $city, $address],
        default=> throw new \InvalidArgumentException('Недопустимый формат данных'),
        };
    }
}
