$(function () {

  $('#select_box_id').change(function()
  {
    // if(select_box_one_value == NULL){
    //   var select_box_one_value = 2013;
    // }else{
          var select_box_one_value = $(this).val();
          var csrf = $("#csrf").val();
          var countryPickedd = $("#countryPicked").val();
        // }
          console.log(select_box_one_value);
          console.log(dataTitle['url']);
          console.log(getUrl.pathname);

          $.ajax
          ({
                //  url: dataTitle['url'],
                url: dataTitle['url'],
                 type: "POST",
                 datatype: "JSON",
                 data: {yearSelection:select_box_one_value, _csrf:csrf, countryPicked:countryPickedd, key:1},
                //  cache: false,
                beforeSend: function (xhr) {
                    $.blockUI({
                            message: 'Processing...',
                            css: {
                                border: 'none',
                                padding: '15px',
                                backgroundColor: '#000',
                                '-webkit-border-radius': '10px',
                                '-moz-border-radius': '10px',
                                opacity: .5,
                                color: '#fff'
                            }
                        });
                    },
                 success: function(msg)
                 {
                   if(msg === null){
                     $.unblockUI();
                     alert("data x retrieve");
                   }else{

                  $.unblockUI();

                   console.log(msg.year);
                   console.log(msg["codeDescImport"]);

                   var totalDataTradeImport = [];
                   var totalDataTradeExport = [];

                   for(var i = 0; i < msg["codeDescImport"].length; i++){
                     totalDataTradeImport.push(parseInt(msg["codeDescImport"][i]));
                     // totalDataTradeImport.push(parseInt(dataPassedImport[i]['TotalDataTrade']));
                   }

                   for(var i = 0; i < msg["codeDescExport"].length; i++){
                     totalDataTradeExport.push(parseInt(msg["codeDescExport"][i]));
                     // totalDataTradeExport.push(parseInt(dataPassedExport[i]['TotalDataTrade']));
                   }

                     Highcharts.chart('containerYear', {
                         chart: {
                             type: 'bar'
                         },
                         title: {
                             text: msg.titleName + ': Exports & Imports by Commodity Section, ' + msg.year,
                             align: 'left',
                             margin: 50
                         },
                         subtitle: {
                             text: ''
                         },
                         lang: {
                             toggleButtonTitle: 'Currency Format',
                             contextButtonTitle: 'Print Chart'
                         },
                         xAxis: {
                             categories: ['0 Food',
                             '1 Beverages and Tobacco',
                             '2 Crude Materials, Inedible',
                             '3 Mineral Fuels, Lubricants, etc.',
                             '4 Animal and Vegetable Oils and Fats',
                             '5 Chemicals',
                             '6 Manufactured Goods',
                             '7 Machinery and Transport Equipment',
                             '8 Miscellaneous Manufactured Articles',
                             '9 Miscellaneous Transactions and Commodities'],
                             title: {
                                 text: null,
                                 // text: '<b>Code Description</b>',
                                 // align: 'high',
                                 // rotation: 0,
                                 // y: -13
                             }
                         },
                         yAxis: {
                             min: 0,
                             title: {
                                 text: '<b>RM Million</b>',
                                 align: 'high'
                             },
                             labels: {
                                 overflow: 'justify',
                                 formatter: function() {
                                   // if (this.value >= 1E9) {
                                   //   return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',') + 'B';
                                   // }else if (this.value >= 1E6 && this.value < 1E9) {
                             	      //  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',') + 'M';
                                      return Highcharts.numberFormat((this.value / 1000000), 0, ',');
                                      // return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                                   // }else{
                                   //   return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',') + 'k';
                                   // }
                                 }
                             }
                         },
                        //  tooltip: {
                        //      // valueSuffix: ' millions'
                        //     //  shared: true,
                        //      formatter: function() {
                        //        return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
                        //        // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
                        //       //  return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 2, '.') + '</b>';
                        //      }
                        //  },
                         plotOptions: {
                             bar: {
                                 dataLabels: {
                                     enabled: true
                                 }
                             }
                         },
                         legend: {
                             layout: 'vertical',
                             align: 'right',
                             // verticalAlign: 'top',
                             // x: -40,
                             y: -200,
                             // floating: true,
                             borderWidth: 1,
                             verticalAlign: 'middle',
                             backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                             shadow: true
                         },
                         credits: {
                             enabled: false
                         },
                         // series: [{
                         //     name: 'Import',
                         //     data: [107, 31, 635, 203, 2, 45, 567, 789, 321, 688]
                         // }, {
                         //     name: 'Export',
                         //     data: [133, 156, 947, 408, 6, 46, 765, 954, 234, 45]
                         // }],
                         series: [{
                             name: 'Import',
                             data: totalDataTradeImport,
                             tooltip: {
                               pointFormatter: function() {
                                 return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
                               }
                             }
                             // data: [parseInt(dataPassed['ic49']),
                             // parseInt(dataPassed['ic50']),
                             // parseInt(dataPassed['ic51']),
                             // parseInt(dataPassed['ic52']),
                             // parseInt(dataPassed['ic53']),
                             // parseInt(dataPassed['ic54']),
                             // parseInt(dataPassed['ic55']),
                             // parseInt(dataPassed['ic56']),
                             // parseInt(dataPassed['ic57']),
                             // parseInt(dataPassed['ic58'])]
                         }, {
                             name: 'Export',
                             data: totalDataTradeExport,
                             tooltip: {
                               pointFormatter: function() {
                                 return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
                               }
                             }
                             // data: [parseInt(dataPassed['ec49']),
                             // parseInt(dataPassed['ec50']),
                             // parseInt(dataPassed['ec51']),
                             // parseInt(dataPassed['ec52']),
                             // parseInt(dataPassed['ec53']),
                             // parseInt(dataPassed['ec54']),
                             // parseInt(dataPassed['ec55']),
                             // parseInt(dataPassed['ec56']),
                             // parseInt(dataPassed['ec57']),
                             // parseInt(dataPassed['ec58'])]
                         }],
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
                                    //  symbolStroke: '#337ab7',
                                     // symbolSize: 18,
                                 },
                             toggle: {
                                         // align: 'left',
                                         _titleKey: 'toggleButtonTitle',
                                         // text: 'Number Format',
                                        //  text: '$',
                                        symbol: 'url(/perangkaan_metsonline/web/images/dollar7p6.png)',
                                        symbolX:20,
                                        symbolY:18,
                                         menuItems: [{
                                           text: 'Billions',
                                           onclick: function () {
                                             this.yAxis[0].update({
                                               labels: {
                                                 formatter: function() {
                                                  //  return Highcharts.numberFormat((this.value / 1000000000),toFixed(2), 0, ',');
                                                   return Highcharts.numberFormat((this.value / 1000000000), 0, ',');
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
                                           text: 'Millions',
                                           onclick: function () {
                                               // alert('You pressed m button!');
                                             this.yAxis[0].update({
                                               labels: {
                                                 formatter: function() {
                                                  //  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
                                                   return Highcharts.numberFormat((this.value / 1000000), 0, ',');
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
                                           text: 'Hundred Thousands',
                                           onclick: function () {
                                             this.yAxis[0].update({
                                               labels: {
                                                 formatter: function() {
                                                  //  return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',');
                                                   return Highcharts.numberFormat((this.value / 100000), 0, ',');
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
                   }
                 }
          });
  });

  var totalDataTradeImport = [];
  var totalDataTradeExport = [];

  for(var i = 0; i < dataPassedImport2.length; i++){
    totalDataTradeImport.push(parseInt(dataPassedImport2[i]));
    // totalDataTradeImport.push(parseInt(dataPassedImport[i]['TotalDataTrade']));
  }

  for(var i = 0; i < dataPassedExport2.length; i++){
    totalDataTradeExport.push(parseInt(dataPassedExport2[i]));
    // totalDataTradeExport.push(parseInt(dataPassedExport[i]['TotalDataTrade']));
  }

    Highcharts.chart('containerYear', {
        chart: {
            type: 'bar'
        },
        title: {
            text: dataTitle['titleName'] + ': Exports & Imports by Commodity Section, ' + dataTitle['dataYear'],
            align: 'left',
            margin: 50
        },
        subtitle: {
            text: ''
        },
        lang: {
            toggleButtonTitle: 'Currency Format',
            contextButtonTitle: 'Print Chart'
        },
        xAxis: {
            categories: ['0 Food',
            '1 Beverages and Tobacco',
            '2 Crude Materials, Inedible',
            '3 Mineral Fuels, Lubricants, etc.',
            '4 Animal and Vegetable Oils and Fats',
            '5 Chemicals',
            '6 Manufactured Goods',
            '7 Machinery and Transport Equipment',
            '8 Miscellaneous Manufactured Articles',
            '9 Miscellaneous Transactions and Commodities'],
            title: {
                text: null,
                // text: '<b>Code Description</b>',
                // align: 'high',
                // rotation: 0,
                // y: -13
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '<b>RM Million</b>',
                align: 'high'
            },
            labels: {
                overflow: 'justify',
                formatter: function() {
                  // if (this.value >= 1E9) {
                  //   return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',') + 'B';
                  // }else if (this.value >= 1E6 && this.value < 1E9) {
            	      //  return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',') + 'M';
                    return Highcharts.numberFormat((this.value / 1000000), 0, ',');
                    //  return Highcharts.numberFormat((this.value / 1000000), 2, '.');
                  // }else{
                  //   return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',') + 'k';
                  // }
                }
            }
        },
        // tooltip: {
        //     // valueSuffix: ' millions'
        //     formatter: function() {
        //       return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
        //       // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 0, ',') + '</b>';
        //       // return '<b>' + this.x + '</b><br> ' + this.series.name + ' : RM <b>' + Highcharts.numberFormat(this.y, 2, '.') + '</b>';
        //     }
        // },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true,
                    // enabled: false,
                    formatter: function() {
                      // return Highcharts.numberFormat((this.y / 1000000).toFixed(2), 0, ',');
                      return Highcharts.numberFormat(this.y, 0, ',');
                    }
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            // verticalAlign: 'top',
            // x: -40,
            y: -200,
            // floating: true,
            borderWidth: 1,
            verticalAlign: 'middle',
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        // series: [{
        //     name: 'Import',
        //     data: [107, 31, 635, 203, 2, 45, 567, 789, 321, 688]
        // }, {
        //     name: 'Export',
        //     data: [133, 156, 947, 408, 6, 46, 765, 954, 234, 45]
        // }],
        series: [{
            name: 'Import',
            data: totalDataTradeImport,
            tooltip: {
              pointFormatter: function() {
                return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              }
            }
            // data: [parseInt(dataPassed['ic49']),
            // parseInt(dataPassed['ic50']),
            // parseInt(dataPassed['ic51']),
            // parseInt(dataPassed['ic52']),
            // parseInt(dataPassed['ic53']),
            // parseInt(dataPassed['ic54']),
            // parseInt(dataPassed['ic55']),
            // parseInt(dataPassed['ic56']),
            // parseInt(dataPassed['ic57']),
            // parseInt(dataPassed['ic58'])]
        }, {
            name: 'Export',
            data: totalDataTradeExport,
            tooltip: {
              pointFormatter: function() {
                return this.series.name + ' : RM <b>' + Highcharts.numberFormat((this.y / 1000000), 0, ',') + '</b>';
              }
            }
            // data: [parseInt(dataPassed['ec49']),
            // parseInt(dataPassed['ec50']),
            // parseInt(dataPassed['ec51']),
            // parseInt(dataPassed['ec52']),
            // parseInt(dataPassed['ec53']),
            // parseInt(dataPassed['ec54']),
            // parseInt(dataPassed['ec55']),
            // parseInt(dataPassed['ec56']),
            // parseInt(dataPassed['ec57']),
            // parseInt(dataPassed['ec58'])]
        }],
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
                          text: 'Billions',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  // return Highcharts.numberFormat((this.value / 1000000000).toFixed(2), 0, ',');
                                  return Highcharts.numberFormat((this.value / 1000000000), 0, ',');
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
                          text: 'Millions',
                          onclick: function () {
                              // alert('You pressed m button!');
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  // return Highcharts.numberFormat((this.value / 1000000).toFixed(2), 0, ',');
                                  return Highcharts.numberFormat((this.value / 1000000), 0, ',');
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
                          text: 'Hundred Thousands',
                          onclick: function () {
                            this.yAxis[0].update({
                              labels: {
                                formatter: function() {
                                  // return Highcharts.numberFormat((this.value / 100000).toFixed(2), 0, ',');
                                  return Highcharts.numberFormat((this.value / 100000), 0, ',');
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
