<?php
class ApiService {
    private $baseUrl = "http://localhost:8080/api";

    public function getCars() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . "/cars");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200 && $response) {
            return json_decode($response, true);
        }
        return []; 
    }
}
?>