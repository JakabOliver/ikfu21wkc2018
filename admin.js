function deleteNews(id){
    let r = confirm("Are you sure you want to delete this News?")
    if(r === true)
    {
        $.ajax({
            method: 'post',
            url: 'delete.php',
            data: {'file' : id , 'token':'za+nt_ec-B_#-r7k_eeN1smV*Cx?s^'},
            success: function (response) {
                if (!response.success) {
                    alert('nem sikerült törölni');
                }
                window.location.href='/media';
                },
            error: function () {
                alert('Something is wrong');
            }
        });
    }
}
function editNews(id){
    let r = confirm("Are you sure you want to edit this news?")
    if(r === true)
    {
        $.ajax({
            method: 'post',
            url: 'get.php',
            data: {'file' : id , 'token':'za+nt_ec-B_#-r7k_eeN1smV*Cx?s^'},
            success: function (response) {
                if (response.success) {
                    let title=response.title;
                    let content=response.content;
                    $('input[name=title]').val(title);
                    $('textarea[name=content]').text(content);
                }
                else {
                    alert('nem sikerült törölni');
                }
                },
            error: function () {
                alert('Something is wrong');
            }
        });
    }
}