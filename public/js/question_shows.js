$('#submit1, #button_building').click(function () {
    if (this.id == 'button_building') {

        $.ajax({
            url: '/geturl',
            type: 'POST',
            dataType: "json",
            data: {
                urls:"https%3A%2F%2Fwww.ozon.ru%2Fcategory%2Fkedy-i-slipony-muzhskie-7660%2F",

            }
        }).data(function(collectData)  {
            alert(123);
        });

    }});

