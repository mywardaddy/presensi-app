$(document).on("click", ".btn-delete", (e) => {
    e.preventDefault();

    const formId = $(e.currentTarget).data("form-id");

    Swal.fire({
        title: "Apakah kamu yakin?",
        text: "Setelah dihapus, data akan hilang permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $(`#${formId}`).submit();
        }
    });
});

window.setTimeout(() => {
    $(".hide-alert")
        .fadeTo(500, 0)
        .slideUp(300, () => {
            $(this).remove();
        });
}, 4000);
