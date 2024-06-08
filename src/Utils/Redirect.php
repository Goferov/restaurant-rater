<?php

namespace App\Utils;

class Redirect {

    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function to(string $to, array $params = [], ?int $code = null):void {
        $location = 'http://' . $this->request->server('HTTP_HOST') . $to . $this->buildUrlParams($params);
        if($code) {
            http_response_code($code);
        }
        header('Location: '.$location);
        exit();
    }

    public function getPreviousPage() {
        $referer = $this->request->server('HTTP_REFERER');
        if (!isset($referer)) {
            return null;
        }
        $parsedUrl = parse_url($referer);
        return $parsedUrl['path'] ?? null;
    }

    private function buildUrlParams($params) {
        if($params) {
            $queryParams = [];
            foreach ($params as $key=>$param) {
                if($param)
                    $queryParams[] = urlencode($key) . '=' . urlencode($param);
            }
            $queryParams = implode('&',$queryParams);
            return '?' . $queryParams;
        }
        return '';
    }
}