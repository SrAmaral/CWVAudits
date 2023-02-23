define([
        'jquery',
        'ko'
    ],
    function( $, ko) {

        return class ChartConfig{
            static TYPE = {
                LINE: 'line'
            }
            constructor(data, options, type = Chart.TYPE.LINE) {
                this.data = data;
                this.options = options;
                this.type = type;
            }
            data; options; type;




        }
    });