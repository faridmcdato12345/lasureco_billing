// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
var ctx = document.getElementById("sales-chart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: 
    {
        labels: _ydata,
        datasets: [
            {
                label: "Last year",
                backgroundColor: "rgb(206,212,218)",
                borderColor: "rgb(206,212,218)",
                data: _xdata_lastyear,
            },
            {
                label: "This year",
                backgroundColor: "rgb(0,123,255)",
                borderColor: "rgb(0,123,255)",
                data: _xdata_thisyear,
            }
        ],
    },
  options: {
    scales: {
        y: {
          beginAtZero: true
        }
    },
    legend: {
      display: false
    }
  }
});