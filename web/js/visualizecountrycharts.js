// $(function () {
//     Highcharts.chart('container', {
//         title: {
//             text: 'Monthly Average Temperature',
//             x: -20 //center
//         },
//         subtitle: {
//             text: 'Source: WorldClimate.com',
//             x: -20
//         },
//         xAxis: {
//             categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
//                 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
//         },
//         yAxis: {
//             title: {
//                 text: 'Temperature (°C)'
//             },
//             plotLines: [{
//                 value: 0,
//                 width: 1,
//                 color: '#808080'
//             }]
//         },
//         tooltip: {
//             valueSuffix: '°C'
//         },
//         legend: {
//             layout: 'vertical',
//             align: 'right',
//             verticalAlign: 'middle',
//             borderWidth: 0
//         },
//         series: [{
//             name: 'Tokyo',
//             data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
//         }, {
//             name: 'New York',
//             data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
//         }, {
//             name: 'Berlin',
//             data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
//         }, {
//             name: 'London',
//             data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//         }]
//     });
// });


/* using json_encode import start */
// $(function () {
//   var $lala = $('#container');
//   // hold country name
//   // var cn = $lala.data('param1');
//   //hold total data trade
//   var tt13 = $lala.data('param2');
//   var tt14 = $lala.data('param3');
//   var tt15 = $lala.data('param4');
//
//   // var dataTrade = <?php ($dataTradeTotalImport) ?>;
//     Highcharts.chart('container', {
//         title: {
//             text: 'Import from ' + dataPassed['countryName'],
//             x: -20 //center
//         },
//         subtitle: {
//             text: '',
//             x: -20
//         },
//         xAxis: {
//             categories: ['2000', '2001', '2002', '2003', '2004', '2005',
//                 '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015']
//         },
//         yAxis: {
//             title: {
//                 text: 'RM '
//             },
//             plotLines: [{
//                 value: 0,
//                 width: 1,
//                 color: '#808080'
//             }]
//         },
//         tooltip: {
//             valueSuffix: ' RM'
//         },
//         legend: {
//             layout: 'vertical',
//             align: 'right',
//             verticalAlign: 'middle',
//             borderWidth: 0
//         },
//         series: [{
//             name: dataPassed['countryName'],
//             data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, tt13, tt14, tt15]
//             // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, tt15[0]['TotalDataTrade'], tt15[1]['TotalDataTrade'], tt15[2]['TotalDataTrade']]
//             // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, dataTrade[0]['TotalDataTrade'], dataTrade[1]['TotalDataTrade'], dataTrade[2]['TotalDataTrade']]
//         }]
//     });
// });
/* using json_encode import end */

/* using data-param import start */
$(function () {
  // var $lala = $('#containerImport');
  // hold country name
  // var cn = $lala.data('param1');
  //hold total data trade
  // var tt13 = $lala.data('param2');
  // var tt14 = $lala.data('param3');
  // var tt15 = $lala.data('param4');

  // var dataTrade = <?php ($dataTradeTotalImport) ?>;

  var dataYear = [];
  var totalDataTrade = [];

  for(var i = 0; i < dataPassedImport.length; i++){
    dataYear.push(dataPassedImport[i]['data_year']);
    // totalDataTrade.push([parseInt(dataPassed2[i]['TotalDataTrade']), parseInt(dataPassed2[i + 1]['TotalDataTrade'])]);
    totalDataTrade.push(parseInt(dataPassedImport[i]['TotalDataTrade']));
  }

    Highcharts.chart('containerImport', {
        title: {
            text: 'Imports from ' + dataTitle['titleName'] + ', ' + dataYear[0] + ' - ' + dataYear[dataYear.length - 1],
            // x: -20 //center
            align: 'left',
            margin: 50
        },
        subtitle: {
            text: '',
            x: -20
        },
        lang: {
            toggleButtonTitle: 'Currency Format',
            contextButtonTitle: 'Print Chart'
        },
        xAxis: {
          title: {
              text: '<b>YEAR</b>',
              align: 'high'
          },
          categories: dataYear
            // categories: ['2000', '2001', '2002', '2003', '2004', '2005',
            //     '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015']
        },
        yAxis: {
          title: {
              text: '<b>RM Million</b>',
              style: {
                fontFamily: 'Arial'
              },
              align:'high',
              rotation:0,
              y: -13,
              // x: 28
          },
            // plotLines: [{
            //     value: 0,
            //     width: 1,
            //     color: '#808080'
            // }],
            // scalable : true
            labels: {
              formatter: function() {
                // if (this.value >= 1E9) {
                //   return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',') + 'B';
                // }else if (this.value >= 1E6 && this.value < 1E9) {
                return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
          	      //  return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                // }else{
                //   return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',') + 'k';
                // }
              }
            }
        },
        // tooltip: {
        //   // valueSuffix: ' RM'
        //   formatter: function() {
        //     return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000).toFixed(2), 0, ',') + '</b>';
        //     // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
        //     // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 2, '.') + '</b>';
        //   }
        // },
        legend: {
            enabled: false,
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: dataTitle['titleName'],
            data: totalDataTrade,
            tooltip: {
              // valueSuffix: ' RM'
              // formatter: function() {
              //   // return '<b>{point.x}</b><br> {series.name} : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              //   return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              //   // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
              //   // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 2, '.') + '</b>';
              // }
              // pointFormat: '<b>{point.x}</b> {series.name} : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>'
              // pointFormat: '{series.name} : RM <b>{point.y}</b>'
              pointFormatter: function() {
                return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              }
            },
            // data: [parseInt(dataPassed['i00']),
            // parseInt(dataPassed['i01']),
            // parseInt(dataPassed['i02']),
            // parseInt(dataPassed['i03']),
            // parseInt(dataPassed['i04']),
            // parseInt(dataPassed['i05']),
            // parseInt(dataPassed['i06']),
            // parseInt(dataPassed['i07']),
            // parseInt(dataPassed['i08']),
            // parseInt(dataPassed['i09']),
            // parseInt(dataPassed['i10']),
            // parseInt(dataPassed['i11']),
            // parseInt(dataPassed['i12']),
            // parseInt(dataPassed['i13']),
            // parseInt(dataPassed['i14']),
            // parseInt(dataPassed['i15'])]
            // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, tt15[0]['TotalDataTrade'], tt15[1]['TotalDataTrade'], tt15[2]['TotalDataTrade']]
            // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, parseInt(dataPassed['i13']), parseInt(dataPassed['i14']), parseInt(dataPassed['i15'])]
        }],
        // navigation: {
        //     buttonOptions: {
        //         symbolStroke: '#f39c12'
        //     }
        // },
        navigation: {
            buttonOptions: {
                theme: {
                    // 'stroke-width': 2,
                    // stroke: 'silver',
                    // // stroke: '#337ab7',
                    // // fill: '#337ab7',
                    // r: 4,
                    states: {
                        hover: {
                            // fill: '#337ab7'
                            'stroke-width': 2,
                            stroke: '#337ab7',
                            // stroke: '#337ab7',
                            // fill: '#337ab7',
                            r: 3,
                        },
                        select: {
                            // stroke: '#039',
                            // fill: '#337ab7'
                            'stroke-width': 2,
                            stroke: '#337ab7',
                            // stroke: '#337ab7',
                            // fill: '#337ab7',
                            r: 3,
                        }
                    }
                }
            }
        },
        credits: {
          enabled: false
        },
        exporting: {
          // type: 'image/jpeg'
          enabled: true,
          buttons: {
            contextButton: {
                    _titleKey: 'contextButtonTitle',
                    // symbolStroke: '#337ab7',
                    // symbolSize: 18,
                },
            // customButton: {
            //     symbol: 'url(/perangkaan_metsonline/web/images/dollar6.png)',
            //     // symbol: 'fa-bitcoin',
            //     // text: 'fa-bitcoin',
            //     // height:20,
            //     // width:20,
            //     // symbolSize:20,
            //     symbolX:20,
            //     symbolY:19,
            //     // x: -62,
            //     // symbolFill: 'blue',
            //     // symbolStroke: 'blue',
            //     // hoverSymbolFill: '#779ABF',
            // },
            toggle: {
                        // align: 'left',
                        _titleKey: 'toggleButtonTitle',
                        // text: '$',
                        symbol: 'url(/perangkaan_metsonline/web/images/dollar7p6.png)',
                        symbolX:20,
                        symbolY:18,
                        // symbolSize: 18,
                        // symbol: 'url(/perangkaan_metsonline/web/images/asas1.png)',
                        menuItems: [{
                          text: 'Billion',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 1000000000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Billion</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }, {
                          text: 'Million',
                          onclick: function () {
                              // alert('You pressed m button!');
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Million</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }, {
                          text: 'Hundred Thousand',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 100000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Hundred Thousand</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 100000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }]
                    },
          }
        }
    });
});
/* using data-param import end */

/* using data-param export start */
$(function () {
  // var $lala = $('#containerExport');
  // hold country name
  // var cn = $lala.data('param1');
  //hold total data trade
  // var tt13 = $lala.data('param2');
  // var tt14 = $lala.data('param3');
  // var tt15 = $lala.data('param4');

  // var dataTrade = <?php ($dataTradeTotalImport) ?>;

  var dataYear = [];
  var totalDataTrade = [];

  for(var i = 0; i < dataPassedExport.length; i++){
    dataYear.push(dataPassedExport[i]['data_year']);
    // totalDataTrade.push([parseInt(dataPassed2[i]['TotalDataTrade']), parseInt(dataPassed2[i + 1]['TotalDataTrade'])]);
    totalDataTrade.push(parseInt(dataPassedExport[i]['TotalDataTrade']));
  }

    Highcharts.chart('containerExport', {
        title: {
            text: 'Exports from ' + dataTitle['titleName'] + ', ' + dataYear[0] + ' - ' + dataYear[dataYear.length - 1],
            // x: -20 //center
            align: 'left',
            margin: 50
        },
        subtitle: {
            text: '',
            x: -20
        },
        lang: {
            toggleButtonTitle: 'Currency Format',
            contextButtonTitle: 'Print Chart'
        },
        xAxis: {
          title: {
              text: '<b>YEAR</b>',
              align: 'high'
          },
          categories: dataYear
            // categories: ['2000', '2001', '2002', '2003', '2004', '2005',
            //     '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015']
        },
        yAxis: {
          title: {
              text: '<b>RM Million</b>',
              style: {
                fontFamily: 'Arial'
              },
              align:'high',
              rotation:0,
              y: -13,
              // x: 28
          },
            // plotLines: [{
            //     value: 0,
            //     width: 1,
            //     color: '#808080'
            // }],
            // scalable : true
            labels: {
              formatter: function() {
                // if (this.value >= 1E9) {
                //   return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',') + 'B';
                // }else if (this.value >= 1E6 && this.value < 1E9) {
                  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
          	      //  return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                // }else{
                //   return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',') + 'k';
                // }
              }
            }
        },
        // tooltip: {
        //   // valueSuffix: ' RM'
        //   pointFormat: '{series.name} : RM <b>{point.y}</b>'
        //   // formatter: function() {
        //   //   return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000).toFixed(2), 0, ',') + '</b>';
        //   //   // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
        //   //   // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 2, '.') + '</b>';
        //   // }
        // },
        legend: {
            enabled: false,
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: dataTitle['titleName'],
            data: totalDataTrade,
            tooltip: {
              pointFormatter: function() {
                return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              }
            }
            // data: [parseInt(dataPassed['e00']),
            // parseInt(dataPassed['e01']),
            // parseInt(dataPassed['e02']),
            // parseInt(dataPassed['e03']),
            // parseInt(dataPassed['e04']),
            // parseInt(dataPassed['e05']),
            // parseInt(dataPassed['e06']),
            // parseInt(dataPassed['e07']),
            // parseInt(dataPassed['e08']),
            // parseInt(dataPassed['e09']),
            // parseInt(dataPassed['e10']),
            // parseInt(dataPassed['e11']),
            // parseInt(dataPassed['e12']),
            // parseInt(dataPassed['e13']),
            // parseInt(dataPassed['e14']),
            // parseInt(dataPassed['e15'])]
            // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, tt15[0]['TotalDataTrade'], tt15[1]['TotalDataTrade'], tt15[2]['TotalDataTrade']]
            // data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6, 24.9, parseInt(dataPassed['e13']), parseInt(dataPassed['e14']), parseInt(dataPassed['e15'])]
            // navigation: {

        }],
        //   buttonOptions: {
        //     enabled: true
        //   }
        // },
        navigation: {
            buttonOptions: {
                theme: {
                    // 'stroke-width': 2,
                    // stroke: 'silver',
                    // // stroke: '#337ab7',
                    // // fill: '#337ab7',
                    // r: 4,
                    states: {
                        hover: {
                            // fill: '#337ab7'
                            'stroke-width': 2,
                            stroke: '#337ab7',
                            // stroke: '#337ab7',
                            // fill: '#337ab7',
                            r: 3,
                        },
                        select: {
                            // stroke: '#039',
                            // fill: '#337ab7'
                            'stroke-width': 2,
                            stroke: '#337ab7',
                            // stroke: '#337ab7',
                            // fill: '#337ab7',
                            r: 3,
                        }
                    }
                }
            }
        },
        credits: {
          enabled: false
        },
        exporting: {
          // type: 'image/jpeg'
          enabled: true,
          buttons: {
            contextButton: {
                    _titleKey: 'contextButtonTitle',
                    // symbolStroke: '#337ab7',
                    // symbolSize: 18,
                },
            toggle: {
                        // align: 'left',
                        _titleKey: 'toggleButtonTitle',
                        // text: 'Number Format',
                        // text: '$',
                        symbol: 'url(/perangkaan_metsonline/web/images/dollar7p6.png)',
                        symbolX:20,
                        symbolY:18,
                        menuItems: [{
                          text: 'Billion',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 1000000000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Billion</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }, {
                          text: 'Million',
                          onclick: function () {
                              // alert('You pressed m button!');
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Million</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }, {
                          text: 'Hundred Thousand',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',');
                                  // return Highcharts.numberFormat((this.value / 100000), 2, '.');
                                }
                              },
                              title: {
                                text: '<b>RM Hundred Thousand</b>',
                              }
                            });
                            this.series[0].update({
                              tooltip: {
                                pointFormatter: function() {
                                  return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 100000), 0, ',') + '</b>';
                                }
                                // pointFormat: 'tooltip changed'
                                // valueSuffix: ' RM'
                              }
                            });
                          }
                        }]
                    },
          }
        }
    });
});
/* using data-param export start */
