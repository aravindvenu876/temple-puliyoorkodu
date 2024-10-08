$(document).ready(function () {
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4'
    });
    $('[data-toggle="tooltip"]').tooltip();
    $("#menu").click(function () {
        $("#nav_list_panel").slideToggle("slow");
    });

    $("#re_menu_close").click(function () {
        $("#nav_list_panel").slideToggle("hide");
    });
    $('#nav_list_panel>ul>li>a').on('click', function () {
        $('#nav_list_panel').slideToggle('hide');
    });
    $('.btn_active').on('click', function () {
       alert("tyt");
    });
  
});
// Bar chart//
new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
                label: "Population (millions)",
                backgroundColor: ["#b14b10", "#C86A08", "#BE7B08", "#C83208", "#643113"],
                data: [2478, 5267, 734, 784, 433]
        }
      ]
    },
    options: {
        legend: {
            display: false
        },
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
    }
});

// Bar chart//

// line chart//

new Chart(document.getElementById("line-chart"), {
    type: 'line',
    data: {
        labels: [1500, 1600, 1700, 1750, 1800, 1850, 1900, 1950, 1999, 2050],
        datasets: [{
                data: [86, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                label: "Africa",
                borderColor: "#b14b10",
                fill: false
      }, {
                data: [282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                label: "Asia",
                borderColor: "#C86A08",
                fill: false
      }, {
                data: [168, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                label: "Europe",
                borderColor: "#BE7B08",
                fill: false
      }, {
                data: [40, 20, 10, 16, 24, 38, 74, 167, 508, 784],
                label: "Latin America",
                borderColor: "#C83208",
                fill: false
      }, {
                data: [6, 3, 2, 2, 7, 26, 82, 172, 312, 433],
                label: "North America",
                borderColor: "#643113",
                fill: false
      }
    ]
    },
    options: {
        title: {
            display: true,
            text: 'World population per region (in millions)'
        }
    }
});
// line chart//

// pie chart//
new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [{
            label: "Population (millions)",
            backgroundColor: ["#b14b10", "#C86A08", "#BE7B08", "#C83208", "#643113"],
            data: [2478, 5267, 734, 784, 433]
      }]
    },
    options: {
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
    }
});


// pie chart//


//Doughnut chart//
new Chart(document.getElementById("doughnut-chart"), {
    type: 'doughnut',
    data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
                label: "Population (millions)",
                backgroundColor: ["#b14b10", "#C86A08", "#BE7B08", "#C83208", "#643113"],
                data: [2478, 5267, 734, 784, 433]
        }
      ]
    },
    options: {
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
    }
});
//Doughnut chart//

//Horizontal bar chart//
new Chart(document.getElementById("bar-chart-horizontal"), {
    type: 'horizontalBar',
    data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
                label: "Population (millions)",
                backgroundColor: ["#b14b10", "#C86A08", "#BE7B08", "#C83208", "#643113"],
                data: [2478, 5267, 734, 784, 433]
        }
      ]
    },
    options: {
        legend: {
            display: false
        },
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
    }
});
//Horizontal bar chart//

// Grouped bar chart//

new Chart(document.getElementById("bar-chart-grouped"), {
    type: 'bar',
    data: {
        labels: ["1900", "1950", "1999", "2050"],
        datasets: [
            {
                label: "Africa",
                backgroundColor: "#643113",
                data: [133, 221, 783, 2478]
        }, {
                label: "Europe",
                backgroundColor: "#c83208",
                data: [408, 547, 675, 734]
        }
      ]
    },
    options: {
        title: {
            display: true,
            text: 'Population growth (millions)'
        }
    }
});

// Grouped bar chart//



//positioning//
var color = Chart.helpers.color;

function createConfig(legendPosition, colorName) {
    return {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'My First dataset',
                data: [
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor()
						],
                backgroundColor: color(window.chartColors[colorName]).alpha(0.5).rgbString(),
                borderColor: window.chartColors[colorName],
                borderWidth: 1
					}]
        },
        options: {
            responsive: true,
            legend: {
                position: legendPosition,
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
						}],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
						}]
            },
            title: {
                display: true,
                text: 'Legend Position: ' + legendPosition
            }
        }
    };
}

window.onload = function () {
			[{
        id: 'chart-legend-top',
        legendPosition: 'top',
        color: "red",
			}, {
        id: 'chart-legend-right',
        legendPosition: 'right',
        color: 'blue'
			}, {
        id: 'chart-legend-bottom',
        legendPosition: 'bottom',
        color: 'green'
			}, {
        id: 'chart-legend-left',
        legendPosition: 'left',
        color: 'yellow'
			}].forEach(function (details) {
        var ctx = document.getElementById(details.id).getContext('2d');
        var config = createConfig(details.legendPosition, details.color);
        new Chart(ctx, config);
    });
};
//positioning//
// $('.tab_nav>nav.navbar').on('click', function(){
//     $('.navbar-collapse').collapse('toggle');
// });

// $('.navbar-nav>li>a').on('click', function(){
//     $('.navbar-collapse').collapse('hide');
// });
