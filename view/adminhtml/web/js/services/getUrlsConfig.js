define([
    'jquery',
    'mage/storage',
], function($,storage){

    return function () {
        let textArea = '#cwvaudit_general_urls'
        let urls = $(textArea).prop("value")
        let select = '#cwvaudit_general_url_home'
        let fillOptions = true
        let home = ''

        createOptions(select, prepareData(urls))

        $('.charts-wrapper').trigger('processStart')

        storage.get('/rest/V1/audits/config/home').done(response => {
            home = response[0]
            hasInOptions(home) ? $(`${select} option[value="${home}"]`).attr("selected",true) : null
        }).always(() => {
                $('.charts-wrapper').trigger('processStop')
        })




        $(textArea).bind('input propertychange', function() {

           urls = $('#cwvaudit_general_urls').prop("value")
           fillOptions = true
           createOptions(select, prepareData(urls))

        });

        function prepareData(data){
            return data.split(',')
        }

        function createOptions(obj, data){
            $(select).empty()
            var regex = /^(?:https?:\/\/)?(?:www\.)?[a-zA-Z0-9]+(?:\.[a-zA-Z]+)+$/;
            data.map((data) => {
                let sanatizeData = data.replace(/\n/g, "")
                !hasInOptions(data) && sanatizeData !== '' && regex.test(sanatizeData) ? $(obj).append(new Option(sanatizeData, sanatizeData)) : null
            })
            hasInOptions(home) ? $(`${select} option[value="${home}"]`).attr("selected",true) : null
        }

        function hasInOptions(url) {
            let options = $(`${select} option`)
            let hasIn = false

            for(var i = 0; i < options.length; i++) {
                if(options[i].innerHTML === url) {
                    hasIn = true;
                    break;
                }
            }
            return hasIn
        }
    }
});