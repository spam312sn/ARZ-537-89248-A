jQuery(document).ready(function() {
    var deleteButton = $('button.delete');
    var letter = $('div.letter');
    var actionDelete;
    var actionSeen;
    deleteButton.on("click", function (e) {
        actionDelete = $.ajax({
            url: "/action/delete",
            method: "POST",
            data: {
                "id": this.id,
                "folder": this.getAttribute('data-folderId')
            }
        });
        location.reload();
        return false;
    });
    letter.on("click", function (e) {
        actionSeen = $.ajax({
            url: "/action/seen",
            method: "POST",
            data: {
                "id": this.id,
                "folder": this.getAttribute('data-folderId')
            }
        });
    });
});