document.addEventListener('DOMContentLoaded', () => {
  const primaryColorVal = localStorage.getItem('primaryColor') || getComputedStyle(document.documentElement).getPropertyValue('--primary-color')
  function cssColorToRgb(cssColor) {
    const [r, g, b] = cssColor.split(' ')
    return `rgb(${r}, ${g}, ${b})`
  }
  const primaryColor = cssColorToRgb(primaryColorVal)

  const radialBarChartTwo = document.querySelector('.radial-bar-chart-two')
  if (radialBarChartTwo) {
    const chartData = {
      series: [44, 55, 67, 83],
      chart: {
        height: 350,
        type: 'radialBar'
      },
      colors: ['#00B8D9', primaryColor, '#FFAB00', '#FF5630'],
      plotOptions: {
        radialBar: {
          dataLabels: {
            name: {
              fontSize: '22px'
            },
            value: {
              fontSize: '16px'
            },
            total: {
              show: true,
              label: 'Total',
              formatter: function (w) {
                // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                return 249
              }
            }
          }
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        markers: {
          width: 6,
          height: 6
        }
      },
      labels: ['Age 7-18', 'Age 19-25', 'Age 26-40', 'Age 41-99']
    }
    const chart = new ApexCharts(radialBarChartTwo, chartData)
    chart.render()
  }

  const productCharts = document.querySelectorAll('.product-chart')

  if (productCharts) {
    const chartData = {
      chart: {
        height: 80,
        width: 130,
        type: 'area',
        sparkline: {
          enabled: true
        },
        toolbar: {
          show: false
        },
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800
        }
      },
      grid: {
        show: false
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 1
      },
      series: [
        {
          name: 'Series 1',
          data: [10, 25, 16, 36, 18, 20, 17, 24, 16, 28, 18, 38, 14]
        }
      ],
      tooltip: {
        enabled: false
      },
      colors: [primaryColor],
      fill: {
        colors: [primaryColor],
        opacity: 1,
        type: 'gradient',
        gradient: {
          shade: 'dark',
          type: 'vertical',
          shadeIntensity: 0.3,
          gradientToColors: undefined,
          inverseColors: false,
          opacityFrom: 0.7,
          opacityTo: 0.1,
          stops: [0, 90, 100],
          colorStops: []
        }
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      yaxis: {
        tooltip: {
          enabled: false
          // followCursor: true
        },
        labels: {
          show: false
        }
      }
    }

    productCharts.forEach((pChart) => {
      const chart = new ApexCharts(pChart, chartData)
      chart.render()
    })
  }

  // analytics line chart
  const analyticsLineChart = document.querySelector('.analytics-stat-chart-1')
  if (analyticsLineChart) {
    const chartData1 = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 80,
        width: 230,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#2C7BE5'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false
      }
    }
    const chartData2 = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 80,
        width: 230,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#8E33FF'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false
      }
    }
    const chartData3 = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 80,
        width: 230,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#BD7B00'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false
      }
    }
    const chartData4 = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 80,
        width: 230,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#FF5630'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false
      }
    }

    const chart1 = new ApexCharts(document.querySelector('.analytics-stat-chart-1'), chartData1)
    chart1.render()
    const chart2 = new ApexCharts(document.querySelector('.analytics-stat-chart-2'), chartData2)
    chart2.render()
    const chart3 = new ApexCharts(document.querySelector('.analytics-stat-chart-3'), chartData3)
    chart3.render()
    const chart4 = new ApexCharts(document.querySelector('.analytics-stat-chart-4'), chartData4)
    chart4.render()
  }

  // banking stat chart
  const bankStatChart1 = document.querySelector('.bank-stat-one')
  if (bankStatChart1) {
    const chartData = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 130,
        width: '100%',
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#2C7BE5'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false,
        padding: {
          left: -10,
          right: -5
        }
      }
    }
    const chart1 = new ApexCharts(bankStatChart1, chartData)
    chart1.render()
  }

  // bank stat chart two
  const bankStatChart2 = document.querySelector('.bank-stat-two')
  if (bankStatChart2) {
    const chartData = {
      series: [
        {
          name: 'Desktops',
          data: [5, 38, 52, 58, 85, 60, 40, 48, 38, 18, 29, 38]
        }
      ],
      chart: {
        height: 130,
        width: '100%',
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        lineCap: 'round',
        width: 3
      },
      xaxis: {
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false
        }
      },
      colors: ['#571F9C'],
      yaxis: {
        labels: {
          show: false
        },
        tooltip: {
          enabled: false
        },
        min: 0,
        max: 85
      },
      grid: {
        show: false,
        padding: {
          left: -10,
          right: -5
        }
      }
    }
    const chart1 = new ApexCharts(bankStatChart2, chartData)
    chart1.render()
  }

  // booking stat progress chart
  const progressCharts = document.querySelectorAll('.progress-chart')
  if (progressCharts.length) {
    progressCharts.forEach((el) => {
      const chart = new ApexCharts(el, {
        chart: {
          type: 'radialBar',
          width: '58%',
          height: 140,
          sparkline: {
            enabled: false
          }
        },
        series: ['33.5'],
        legend: {
          show: false
        },
        stroke: {
          lineCap: 'round'
        },
        colors: ['#2C7BE5'],
        plotOptions: {
          radialBar: {
            dataLabels: {
              value: {
                show: false
              },
              name: {
                offsetY: 5
              }
            },
            hollow: {
              size: '55%'
            }
          }
        },
        labels: ['33.5%'],
        grid: {
          padding: {
            top: -16,
            bottom: -20
          }
        }
      })
      chart.render()
    })
  }
})
