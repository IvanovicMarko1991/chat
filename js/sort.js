function selectedSort() {
    let selected = document.getElementById('selected-sort').value;

    $.ajax({

        url: "fetch_sorted.php",
        method: "POST",
        data: {
            selected: selected
        },
        success: function (data) {
            $('#user_details').empty();
            $('#user_details').html(data);
        }
    });

}
