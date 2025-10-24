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
});
