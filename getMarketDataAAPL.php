<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Data Scatter Plot</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@latest"></script>
</head>
<body>

<form id="stockForm">
    <select name="symbol" id="symbolSelect">
        <option value="AAPL">Apple Inc (AAPL)</option>
        <option value="CVS">CVS Health Corp (CVS)</option>
        <option value="META">Meta Platforms Inc (META)</option>
    </select>
    <button type="submit">Load Data</button>
</form>

<canvas id="myScatterChart" width="800" height="400"></canvas>

<script>
// Placeholder for initializing the chart to avoid errors before data is loaded
var myScatterChart = null;

document.getElementById('stockForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent traditional form submission

    var symbol = document.getElementById('symbolSelect').value;
    fetchDataAndUpdateChart(symbol);
});

function fetchDataAndUpdateChart(symbol) {
    var url = 'path_to_your_php_script.php?symbol=' + symbol; // Adjust with the actual PHP script path

    fetch(url)
    .then(response => response.json())
    .then(data => {
        // Assuming 'data' is the processed stock data array for the selected symbol
        updateChart(data);
    })
    .catch(error => console.error('Error fetching data:', error));
}

function updateChart(stockData) {
    var scatterData = stockData.map(function(e) {
        return {
            x: moment(e.date).format('YYYY-MM-DD HH:mm:ss'),
            y: e.high
        };
    });

    if (myScatterChart !== null) {
        myScatterChart.destroy(); // Destroy the previous chart instance if exists
    }

    var ctx = document.getElementById('myScatterChart').getContext('2d');
    myScatterChart = new Chart(ctx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Stock Price',
                data: scatterData,
                backgroundColor: 'rgb(75, 192, 192)'
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        tooltipFormat: 'YYYY-MM-DD HH:mm:ss',
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
}

// Initially load AAPL data or your default choice
fetchDataAndUpdateChart('AAPL');
</script>

</body>
</html>
