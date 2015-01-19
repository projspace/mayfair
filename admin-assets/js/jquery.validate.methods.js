$(function(){
    $.validator.addMethod("onlychars", function(value, element) {
        var $element = $(element)
        var reg = /^[A-Za-z]+$/
        return !reg.test($element.val())
    }, $.validator.messages.required)
    
    $.validator.addMethod("alphanum", function(value, element) {
        var $element = $(element)
        var reg = /^[0-9A-Za-z]+$/
        return !reg.test($element.val())
    }, $.validator.messages.required)
    
    $.validator.addMethod("regex", function(value, element, expr) {
        var $element = $(element)
        var reg = new RegExp(expr, '/')
        return reg.test($element.val());
    }, $.validator.messages.required)
})