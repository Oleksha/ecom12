$(document).ready(function(){
    // Check Admin Password is correct or not
    $("#current_pwd").keyup(function () {
        let current_pwd = $("#current_pwd").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/verify-password",
            data: { current_pwd: current_pwd },
            success: function (response) {
                if (response === "false") {
                    $("#verifyPwd").html("<font color='red'>Введенный пароль не корректен</font>");
                } else {
                    $("#verifyPwd").html("<font color='green'>Введен корректный пароль</font>");
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document).on("click", "#deleteProfileImage", function(){
        if (confirm('Are you sure you want to remove your Profile Image?')) {
            let admin_id = $(this).data('admin-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "delete-profile-image",
                data: { admin_id: admin_id},
                success: function (response) {
                    if (response['status'] === true) {
                        alert(response['message']);
                        $('#profileImageBlock').remove();
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                }
            });
        }
    });

    // Update Subadmin Status
    $(document).on("click", ".updateSubadminStatus", function(){
        let status = $(this).children('i').data('status');
        let subadmin_id = $(this).data('subadmin-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/update-subadmin-status",
            data: { status: status, subadmin_id: subadmin_id},
            success: function (response) {
                if (response['status'] === 0) {
                    $("a[data-subadmin-id='" + subadmin_id + "']").html("<i class='fa fa-toggle-off' style='color: gray' data-status='Inctive'></i>");
                } else if (response['status'] === 1) {
                    $("a[data-subadmin-id='" + subadmin_id + "']").html("<i class='fa fa-toggle-on' style='color: #3f6ed3' data-status='Active'></i>");
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });

    // Update Category Status
    $(document).on("click", ".updateCategoryStatus", function(){
        let status = $(this).children('i').data('status');
        let category_id = $(this).data('category-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/update-category-status",
            data: { status: status, category_id: category_id},
            success: function (response) {
                if (response['status'] === 0) {
                    $("a[data-category-id='" + category_id + "']").html("<i class='fa fa-toggle-off' style='color: gray' data-status='Inctive'></i>");
                } else if (response['status'] === 1) {
                    $("a[data-category-id='" + category_id + "']").html("<i class='fa fa-toggle-on' style='color: #3f6ed3' data-status='Active'></i>");
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });

    $(document).on("click", "#deleteCategoryImage", function(){
        if (confirm('Are you sure you want to remove this Category Image?')) {
            let category_id = $(this).data('category-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/admin/delete-category-image",
                data: { category_id: category_id},
                success: function (response) {
                    if (response['status'] === true) {
                        alert(response['message']);
                        $('#categoryImageBlock').remove();
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                }
            });
        }
    });

    $(document).on("click", "#deleteSizeChartImage", function(){
        if (confirm('Are you sure you want to remove this Size Chart Image?')) {
            let category_id = $(this).data('category-id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "/admin/delete-size-chart-image",
                data: { category_id: category_id},
                success: function (response) {
                    if (response['status'] === true) {
                        alert(response['message']);
                        $('#sizeChartImageBlock').remove();
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                }
            });
        }
    });

    $(document).on('click', '.confirmDelete', function (e) {
        e.preventDefault();
        let button = $(this);
        let module = button.data('module');
        let module_id = button.data('id');
        let form = button.closest('form');
        let redirectUrl = '/admin/delete-' + module + '/' + module_id;
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете это изменить!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33d33',
            confirmButtonText: 'Да, удалить!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Проверяем, существует ли форма и есть ли маршрут удаления.
                if (form.length > 0 && form.attr('action') && form.attr('method') === 'POST') {
                    // Создать и добавляет скрытый input _method, если он отсутствует.
                    if (form.find("input['_method']").length === 0) {
                        form.append('<input type="hidden" name="_method" value="DELETE">');
                    }
                    form.submit(); // Отправить форму (используется в модуле категорий)
                } else {
                    // Перенаправление, если отсутствует форма удаления.
                    window.location.href = redirectUrl;
                }
            }
        });
    });

    // Update Product Status
    $(document).on("click", ".updateProductStatus", function(){
        let status = $(this).children('i').data('status');
        let product_id = $(this).data('product-id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "/admin/update-product-status",
            data: { status: status, product_id: product_id},
            success: function (response) {
                if (response['status'] === 0) {
                    $("a[data-product-id='" + product_id + "']").html("<i class='fa fa-toggle-off' style='color: gray' data-status='Inctive'></i>");
                } else if (response['status'] === 1) {
                    $("a[data-product-id='" + product_id + "']").html("<i class='fa fa-toggle-on' style='color: #3f6ed3' data-status='Active'></i>");
                }
            },
            error: function () {
                alert("Error");
            }
        });
    });
});
