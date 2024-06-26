setupFormLoadingImage();
function setupFormLoadingImage(){
    $('form').submit(function(event){
        $('.busy').show('fast');
        return true;
    });
}

function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

function toggleSidebar(){
    var position = 0;
    if( $('#sidebar').position().left == 0) {
        position = -350;
    }
    $('#sidebar').animate({"left":position + "px"}, 200);
}

var csrf_token = "{{ csrf_token() }}";
$(".delete-resource, .delete-resource-simple").on('click',function(e){
    if (! confirm("Are you sure?")){ return false; }
    else{
        e.preventDefault();
        var url = $(this).attr('href');
        $('<form action="' + url + '" method="POST"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' + csrf_token + '"></form>').appendTo('body').submit();
    }
});

$(function() {
    var params = new window.URLSearchParams(window.location.search);
    if (params.get('filters')) {
        localStorage.setItem('filters', params.get('filters'));
    } else if(window.location.toString().endsWith('tickets') && localStorage.getItem('filters') !== null) {
        location.href = window.location + '?filters=' + localStorage.getItem('filters');
    }
});
