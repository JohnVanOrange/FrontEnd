$(document).ready(function()
{

    $(".menu").click(function()
    {
        var X=$(this).attr('id');
        if(X==1)
        {
            $(".submenu").hide();
            $(this).attr('id', '0');
        }
        else
        {
            $(".submenu").show();
            $(this).attr('id', '1');
        }

    });

    //Mouse click on sub menu
    $(".submenu").mouseup(function()
    {
        return false
    });

    //Mouse click on my menu link
    $(".account").mouseup(function()
    {
        return false
    });

    //Document Click
    $(document).mouseup(function()
    {
        $(".submenu").hide();
        $(".account").attr('id', '');
    });
});