/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    $('.submit').click(function() {
        $(this).parents('form').attr('action', $(this).data('action'));
        $(this).parents('form').submit();
    });
});

