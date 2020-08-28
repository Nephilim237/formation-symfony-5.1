$(document).ready(function(){
    let adImages = $("#a_images");
    let widgetsCount = $('#widgets_counter');
    $('#add-field').click(function(){
        const index = +$(widgetsCount).val();
        const tmpl = adImages.data('prototype').replace(/__name__/g, index);

        $(adImages).append(tmpl);
        $(widgetsCount).val(index + 1);

        handleDeleteButton();
    })

    function handleDeleteButton(){
        $('button[data-action="delete"]').click(function(){
            const target = this.dataset.target;
            $(target).remove();
        })
    }

    function updateCounter(){
        const count = +$("#a_images div.form-group").length;
        $(widgetsCount).val(count);
    }

    updateCounter();
    handleDeleteButton();
})
