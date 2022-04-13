console.log("k");

$("#button_building").click(function()
{

    $.post( "/geturl/", { url:'https://www.ozon.ru/category/kedy-i-slipony-muzhskie-7660/'} );

});
