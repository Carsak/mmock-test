<?php

class Forecast
{
    /**
     * @var string Приватный ключ для доступа к АПИ
     */
    private $appId = 'fc5a93273d538a1ea38cd3aa2849a58f';
    /**
     * @var string Метод  для получения данных
     */
    private $path = '/data/2.5/weather?units=metric';
    /**
     * @var string Урл хоста
     */
    private $host = 'https://api.openweathermap.org';
    /**
     * @var string Название города
     */
    private $city;

    /**
     * @param string $city Название города, для которого нужно узнать погоду
     */
    public function __construct(string $city, string $host = null)
    {
        $this->city = $city;
        if ($host) {
            $this->host = $host;
        }
    }

    /**
     * Получить данные
     * @return array
     */
    public function findOut(): array
    {
        $url  = $this->createUrl();
        $data = $this->simpleParser($url);
        $this->validate($data);

        return $data;
    }

    /**
     * Получить данные из АПИ
     * @param string $url
     * @return string
     */
    private function simpleParser(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, 1);
    }

    /**
     * Создать урл для запроса
     * @return string
     */
    private function createUrl(): string
    {
        return $this->host .  $this->path . "&q={$this->city}" . "&appid={$this->appId}";
    }

    /**
     * Валидация выходящих данных
     * @param array $data
     */
    private function validate(array $data): void
    {
        if ((int)$data['cod'] === 404 || (int)$data['cod'] === 400) {
            throw new \RuntimeException('City Not Found');
        }
    }
}
