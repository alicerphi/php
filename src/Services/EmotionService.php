<?php

namespace ZWorkshop\Services;

use Silex\Application;

class EmotionService
{
    const ENDPOINT = 'https://api.projectoxford.ai/emotion/v1.0/recognize';

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $image
     * @param bool $isUrl
     * @param bool $faceRectangles
     * @param bool $debug
     * @return mixed
     */
    public function analyze($image, $isUrl = false, $faceRectangles = false, $debug = false)
    {
        $ch = curl_init();

        $apiUrl = self::ENDPOINT;
        if ($faceRectangles) {
            $apiUrl .= '?faceRectangles=1';
        }

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $ch);
        if ($debug) {

            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $headers = [
            'Content-Type: ' . ($isUrl ? 'application/json' : 'application/octet-stream'),
            'Ocp-Apim-Subscription-Key: ' . $this->app['config']['emotion_api']['key'],
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POST, true);

        if ($isUrl) {
            $data = json_encode(['url' => $image]);
        } else {
            $data = file_get_contents($image);
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if ($debug) {
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);

            echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
        }

        return json_decode($response);
    }
}