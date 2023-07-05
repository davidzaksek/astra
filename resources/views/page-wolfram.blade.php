<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function query_wolfram($query) {
    $app_id = "U22YAG-X32UYAPYHJ";  // replace with your AppID
    $base_url = "http://api.wolframalpha.com/v1/simple";
    $query = urlencode($query);
    $url = "{$base_url}?input={$query}&appid={$app_id}&format=plaintext";

    // initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the transfer as a string
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status != 200) {
        throw new Exception("Failed to connect to Wolfram Alpha API. HTTP status: $http_status");
    }

    return $response;
}

function parse_wolfram_response($response) {
    $xml = simplexml_load_string($response);
    $plaintext = $xml->xpath('//plaintext');

    foreach ($plaintext as $text) {
        if ((string) $text !== '') {
            return (string) $text;
        }
    }

    return null;
}

$result = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $query = $_POST["query"];
        $response = query_wolfram($query);
        $result = parse_wolfram_response($response);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Wolfram Alpha Query</h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <label for="query">Query:</label><br>
  <input type="text" id="query" name="query" value=""><br>
  <input type="submit" value="Submit">
</form> 

<?php
if (!empty($result)) {
    echo "<h2>Result:</h2>";
    echo "<p>" . htmlspecialchars($result) . "</p>";
}

if (!empty($error)) {
    echo "<h2>Error:</h2>";
    echo "<p>" . htmlspecialchars($error) . "</p>";
}
?>

</body>
</html>