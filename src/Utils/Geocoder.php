<?php

namespace Taskforce\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Geocoder
{
    /**
     * Получение данных о местоположении по заданному адресу или координатам.
     *
     * @param string $location Адрес для определения координат или координаты для определения адреса.
     * @param string $format Формат данных, который необходимо вернуть. Возможные значения:
     *  - 'coordinates' - координаты в формате [долгота, широта];
     *  - 'city' - название города;
     *  - 'address' - адрес объекта;
     *  - 'allData' - все данные в формате [координаты, название города, адрес объекта].
     * @return null|string|array Массив или строка с данными в зависимости от заданного формата, null, если невозможно определить локацию.
     * @throws \RuntimeException Если произошла ошибка при запросе к API или при парсинге ответа от API.
     * @throws \InvalidArgumentException Если задан недопустимый формат данных.
     */
    public static function getLocationData(string $location, string $format = 'allData'): null | string | array
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
        $city = self::getCityName($geoObject['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']); // getCityName() выглядит как какая-то костыльная дичь, но я не нашел более вменяемого способа гарантированно получить название города, так как ключ 'LocalityName' может находиться по разным путям, а может и вовсе отсутствовать, тогда надо искать значение 'AdministrativeAreaName'. А в $geoObject['description'] тоже название города будет не всегда, хоть и часто. В общем, это лучшее, что я смог придумать.
        $address = $geoObject['name'];

        return match($format) {
            'coordinates' => $coordinates,
            'city' => $city,
            'address' => $address,
            'allData' => ['coordinates' => $coordinates, 'city' => $city, 'address' => $address],
        default=> throw new \InvalidArgumentException('Недопустимый формат данных'),
        };
    }

    /**
     * Рекурсивно ищет значение ключа 'LocalityName' в массиве и возвращает его. Если не находит, то возвращает значение ключа 'AdministrativeAreaName'.
     *
     * @param array $array Массив, в котором производится поиск.
     *
     * @return string|null Значение искомого ключа, или null, если ключ не найден.
     */
    public static function getCityName(array $array): ?string
    {
        foreach ($array as $key => $value) {
            if ($key === 'LocalityName') {
                return $value;
            }
            if (is_array($value)) {
                $result = self::getCityName($value);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return $array['AdministrativeAreaName'] ?? null;
    }
}
