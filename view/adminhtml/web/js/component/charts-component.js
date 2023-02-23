define([
    'uiComponent',
    'jquery',
    'mage/storage',
    'ko',
    'chartConfig'
    ],
    function(Component, $,storage, ko, ChartConfig) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_CWVAudit/charts-component',
                audits: ko.observable([]),
            },

            initialize: function () {
                this._super()
                self = this;

                self.getLists()

                self.audits.subscribe(() => {
                    this.prepareData()
                })


            },

            getLists: function () {
                $('.charts-wrapper').trigger('processStart')
                storage.get('/rest/V1/audits').done(response => {
                    self.audits(response)
                })
                .always(() => {
                    $('.charts-wrapper').trigger('processStop') // Remove 0 spiner de loading na pagina
                })
            },

            prepareData: function () {
                let data = {}
                let labels = []

                const changeDateFormet=(date)=>{
                    const [year, month, day] = date.split('-');
                    return [day, month, year].join('-');
                }

                self.audits().map(audit => {
                    !!data[audit.url] ? data[audit.url].push(audit) : data[audit.url] = [audit]

                    if(!labels.includes(changeDateFormet(audit.update_at.split(" ")[0]))) {
                        labels.push(changeDateFormet(audit.update_at.split(" ")[0]))
                    }
                })


                const options = {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Performace Chart',
                            font: {
                                size: 24
                            }
                        },

                        tooltip: {
                            bodyFont: {
                                weight: 700
                            },
                            bodySpacing: 5,
                            callbacks: {
                                afterLabel: function(context) {
                                    return self.externalTooltipHandler(context)
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 100
                        }
                    }
                }

                self.renderChart(labels, data, options)

            },

            renderChart: function (labels, data, options, type = 'line') {

                let dataSets = []

                let urls = Object.keys(data)

                console.log()

                urls.map((url, index) => {
                    let color = '#'+ Math.floor(Math.random() * 19777215).toString(16)
                    let performaces = []
                    let urlData = data[url].map(dataUrl => {
                        performaces.push(dataUrl.performace)
                        return dataUrl

                    })
                    dataSets.push({
                        label: urlData[0].url,
                        data: performaces,
                        borderColor: color,
                        backgroundColor: color,
                        extra_data: urlData

                    })

                })

                let finalData = {
                    labels: labels,
                    datasets: [...dataSets]
                }

                let canvas = document.getElementById('myChart')

                let config = new ChartConfig(data, options, type )

                new Chart(canvas, {
                    type: config.type,
                    data: finalData,
                    options: options
                })

            },




            externalTooltipHandler: function (context){
                let audit = context.dataset.extra_data[context.dataIndex]
                let finalAudit = []

                for(let data in audit) {
                    if( !['id', 'url', 'update_at'].includes(data)){
                        switch (data) {
                            case 'performace':
                                addAudit('Performace', 'performace', false);
                                break;
                            case 'first_content_paint' :
                                addAudit('FCP', 'first_content_paint');
                                break;
                            case 'speed_index' :
                                addAudit('Speed Index', 'speed_index');
                                break;
                            case 'largest_content_paint' :
                                addAudit('LCP', 'largest_content_paint');
                                break;
                            case 'time_to_interactive' :
                                addAudit('Time To Interactive', 'time_to_interactive');
                                break;
                            case 'total_blocking_time' :
                                addAudit('TBT', 'total_blocking_time');
                                break;
                            case 'cumulative_layout_shift' :
                                addAudit('CLS', 'cumulative_layout_shift', false);
                                break;
                        }

                    }
                }

                function addAudit (metric, data, noTime = true) {
                    finalAudit.push(`${metric}:  ${audit[data]} ${noTime ? 's' : ''}`)
                }

                return finalAudit;
            }





        });
});