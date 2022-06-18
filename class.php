<?php
// This file used to generate Token and Curl calls
class Token
{
	// private constructor function
	// to prevent external instantiation
	public function Token()
	{
	}

	function base64url_encode($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
	function base64url_decode($data)
	{
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	//to generate a token and get an access token
	private function generateToken()
	{
		$header = ['typ' => 'JWT', 'alg' => 'HS256'];
		$header = json_encode($header);
		$header = base64_encode($header);
		$CLIENT_KEY = CLIENT_KEY;
		$AUD_VALUE = AUD_VALUE;
		$CLIENT_SECRET = CLIENT_SECRET;
		$nowtime = time();
		$exptime = $nowtime + 599;

		$payload = "{
		\"iss\": \"$CLIENT_KEY\",
		\"aud\": \"$AUD_VALUE\",
		\"exp\": $exptime,
		\"iat\": $nowtime}";
		$payload = $this->base64url_encode($payload);
		$signature = $this->base64url_encode(hash_hmac('sha256', "$header.$payload", $CLIENT_SECRET, true));
		$assertionValue = "$header.$payload.$signature";
		$grant_type = "urn:ietf:params:oauth:grant-type:jwt-bearer";
		$grant_type = urlencode($grant_type);
		$postField = "grant_type=" . $grant_type . "&assertion=" . $assertionValue;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $AUD_VALUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postField);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/x-www-form-urlencoded",
			"cache-control: no-cache"
		));
		$response = curl_exec($ch);
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		if ($response === false) {
			// echo 'Curl error: ' . curl_error($ch);
			return false;
		}
		curl_close($ch);
		$tokenArray = json_decode($response, true);
		return $tokenArray['access_token'];
	}

	public function saveForm($jsonPostFields)
	{
		if ($this->generateToken() != false) {
			$JWToken = $this->generateToken();
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_URL, RECORDURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostFields);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"cache-control: no-cache",
				"Authorization: Bearer $JWToken"
			));
			$response = curl_exec($ch);
			if ($response === false) {
				// echo 'Curl error: ' . curl_error($ch1);
				return false;
			}
			curl_close($ch);
			return $response;
		} else {
			return false;
		}
	}

	public function getForm($tempUrl)
	{
		if ($this->generateToken() != false) {
			$url = RECORDURL . $tempUrl;
			$JWToken = $this->generateToken();
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, FALSE);
			// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPostFields);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"cache-control: no-cache",
				"Authorization: Bearer $JWToken"
			));
			$response = curl_exec($ch);
			if ($response === false) {
				// echo 'Curl error: ' . curl_error($ch1);
				return false;
			}
			curl_close($ch);
			return $response;
		} else {
			return false;
		}
	}
}
