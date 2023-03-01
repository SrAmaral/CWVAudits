define([
        'uiComponent',
        'jquery',
        'mage/storage',
        'ko',
        'chartConfig',
        'chartJs'
    ],
    function(Component, $,storage, ko, ChartConfig, Chart) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Webjump_CWVAudit/charts-component',
                audits: ko.observable([]),
                showDays: ko.observable(14),
                initialDay: ko.observable(new Date(Date.now() - 13 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10)),
                chart: ko.observable(),
                homeUrl: ko.observable(),
                homeAudit: ko.observable({})
            },

            initialize: function () {
                this._super()
                self = this;

                self.audits.subscribe(() => {
                    this.prepareData()
                })
                self.showDays.subscribe(() => {
                    self.chart().destroy()
                    self.prepareData()
                })
                self.initialDay.subscribe(() => {
                    self.chart().destroy()
                    self.prepareData()
                })

                self.homeAudit.subscribe(() => {
                })

            },

            getLists: function () {
                $('.charts-wrapper').trigger('processStart')
                storage.get('/rest/V1/audits').done(response => {
                    self.audits(response)
                })
                storage.get('/rest/V1/audits/config/home').done(response => {
                    self.homeUrl(response[0])
                })
                    .always(() => {
                        $('.charts-wrapper').trigger('processStop')
                    })
            },

            prepareData: function () {
                let data = {}
                let labels = []

                self.audits().map(audit => {
                    !!data[audit.url] ? data[audit.url].push(audit) : data[audit.url] = [audit]

                    // if(!labels.includes(self.changeDateFormet(audit.update_at.split(" ")[0]))) {
                    //     labels.push(self.changeDateFormet(audit.update_at.split(" ")[0]))
                    // }
                })

                let validHomeAudits = []

                let auditsHome = self.audits().filter(audit => self.homeUrl() === audit.url)



               let finalHomeAudit = {}

                !!auditsHome ? finalHomeAudit = {
                    performace: ko.observable(auditsHome[auditsHome.length -1]?.performace),
                        first_content_paint: ko.observable(auditsHome[auditsHome.length -1]?.first_content_paint),
                    speed_index: ko.observable(auditsHome[auditsHome.length -1]?.speed_index),
                    largest_content_paint: ko.observable(auditsHome[auditsHome.length -1]?.largest_content_paint),
                    time_to_interactive: ko.observable(auditsHome[auditsHome.length -1]?.time_to_interactive),
                    total_blocking_time: ko.observable(auditsHome[auditsHome.length -1]?.total_blocking_time),
                    cumulative_layout_shift: ko.observable(auditsHome[auditsHome.length -1]?.cumulative_layout_shift),
                    update_at: ko.observable(self.changeDateFormet(auditsHome[auditsHome.length -1]?.update_at.split(' ')[0])),
                    url: ko.observable(auditsHome[auditsHome.length -1]?.url)|| null
                } :null
               self.homeAudit(finalHomeAudit)

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

                self.renderChart(self.getLastDays(), data, options)

            },

            renderChart: function (labels, data, options, type = 'line') {

                let dataSets = []
                let urls = Object.keys(data)
                let urlData = ''
                urls.map((url, index) => {
                    let color = '#'+ Math.floor(Math.random() * 19777215).toString(16)
                    const allDays = self.getLastDays()
                    const extraData = new Array(allDays.length).fill(null)
                    let performaces = new Array(allDays.length).fill(null)

                    let urlData = data[url].map(dataUrl => {
                        let validate = self.changeDateFormet(dataUrl.update_at.split(' ')[0])


                        performaces[allDays.indexOf(validate)] = dataUrl.performace
                        extraData[allDays.indexOf(validate)] = dataUrl
                        return dataUrl

                    })

                    dataSets.push({
                        label: urlData[0].url,
                        data: performaces,
                        borderColor: color,
                        backgroundColor: color,
                        extra_data: extraData
                    })

                })

                let finalData = {
                    labels: labels,
                    datasets: [...dataSets]
                }
                let canvas = document.getElementById('myChart')

                let config = new ChartConfig(data, options, type )

                let mychart = new Chart(canvas, {
                    type: config.type,
                    data: finalData,
                    options: options
                })

                self.chart(mychart)
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
            },

            changeDateFormet: function (date){
                if(!!date) {
                    const [year, month, day] = date.split('-');
                    return [day, month, year].join('-');
                }
            },

            getLastDays: function () {
                let days = [];
                let dayZero = new Date(self.initialDay())
                for (let i = self.showDays() -1; i >= 0; i--) {
                    let data = new Date(dayZero.getTime());
                    data.setDate(data.getDate() + i);
                    days.push(self.changeDateFormet(data.toISOString().slice(0, 10)));
                }
                return days.reverse()
            },

            getBackgorundPerformace: function (performace) {
                if(!!performace) {
                    return performace() <= 49 ? '#FFEBEB' : performace() >= 50 && performace() < 90 ? '#FFF7EB' : '#E6FAF0'
                }
            },

            getTextColor: function (metric, value) {
                if(!!value) {
                    switch (metric) {
                        case 'performace':
                            return value() <= 49 ? '#00CC66' : value() >= 50 && value() < 90 ? '#FFAA33' : '#FF3333'
                        case 'first_content_paint' :
                            return value() < 1.8 ? '#00CC66' : value() >= 1.8 && value() < 3 ? '#FFAA33' : '#FF3333'
                        case 'speed_index' :
                            return value() < 3.4 ? '#00CC66' : value() >= 3.4 && value() < 5.8 ? '#FFAA33' : '#FF3333'
                        case 'largest_content_paint' :
                            return value() < 2.5 ? '#00CC66' : value() >= 2.5 && value() < 4 ? '#FFAA33' : '#FF3333'
                        case 'time_to_interactive' :
                            return value() < 3.9 ? '#00CC66' : value() >= 3.9 && value() < 7.3 ? '#FFAA33' : '#FF3333'
                        case 'total_blocking_time' :
                            return value() < 200 ? '#00CC66' : value() >= 200 && value() < 600 ? '#FFAA33' : '#FF3333'
                        case 'cumulative_layout_shift' :
                            return value() <= 0.1 ? '#00CC66' : value() > 0.1 && value() < 0.25 ? '#FFAA33' : '#FF3333'
                    }
                }
            }

        });
    });