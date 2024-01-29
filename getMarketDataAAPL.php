<!DOCTYPE html>
<html>
<head>
    <title>Stock Data Scatter Plot</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@latest"></script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="stocks.html">Stocks</a></li>
                <li><a href="publishers.html">Publisher Ratings</a></li>
                <li><a href="getMarketDataAAPL.php">AAPL Scatter Plot</a></li>
            </ul>
        </nav>
    </header>
    <div id="myChartContainer">
    <canvas id="myChart"></canvas>
    </div>


    <?php
    $queryString = http_build_query([
        'access_key' => 'e3365fb1e8249b58a90facbfc3eb3e9f',
        'symbols' => 'AAPL',
        'interval' => '1min',
    ]);

    $ch = curl_init(sprintf('%s?%s', 'https://api.marketstack.com/v1/intraday/2024-01-25', $queryString));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $json = curl_exec($ch);
    if ($json === false) {
        echo "CURL Error: " . curl_error($ch);
    } else {
        $apiResult = json_decode($json, true);
        if (isset($apiResult['data']) && is_array($apiResult['data'])) {
            $scatterData = [];
            foreach ($apiResult['data'] as $stockData) {
                $scatterData[] = [
                    'x' => $stockData['date'],
                    'y' => $stockData['high'], 
                ];            }
        } else {
            echo "Error: Data not found in API response.";
        }
    }
    curl_close($ch);

    // Convert data to JSON for JavaScript
    $jsonScatterData = json_encode($scatterData);
    ?>

    <script>
    var scatterData = <?php echo $jsonScatterData; ?>;

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Stock Data',
                data: scatterData,
                backgroundColor: 'rgb(75, 192, 192)'
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        parser: 'YYYY-MM-DDTHH:mm:ssZ',
                        tooltipFormat: 'll HH:mm'
                    },
                    title: {
                        display: true,
                        text: 'Date and Time'
                    }
                },
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Price'
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
