get = (route) => {
    var ajax = new Promise(function(resolve,reject) {
        $.ajax({
            url: route,
            type: "GET",
            success : function(result) {
                resolve(result)
            },
            error : function(err) {
                reject(err)
            }
        })
    })

    return ajax
}

save = (data, route, token) => {
    var ajax = new Promise(function(resolve,reject) {
            $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: route,
            type: "POST",
            data: data,
            success : function(result) {
                resolve(result)
            },
            error : function(err) {
                reject(err)
            }
        })
    })

    return ajax
}

saveFile = (data, route, token) => {
    var ajax = new Promise(function(resolve,reject) {
            $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: route,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            success : function(result) {
                resolve(result)
            },
            error : function(err) {
                reject(err)
            }
        })
    })

    return ajax
}

update = (data, route, token) => {
    var ajax = new Promise(function(resolve,reject) {
            $.ajax({
            headers: {
                'X-CSRF-Token': token
            },
            url: route,
            type: "PUT",
            data: data,
            success : function(result) {
                resolve(result)
            },
            error : function(err) {
                reject(err)
            }
        })
    })

    return ajax
}

remove = (data, route, token) => {
    var ajax = new Promise(function(resolve,reject) {
        $.ajax({
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': token
            },
            url: route,
            dataType: 'JSON',
            cache: false,
            data: data,
            success: function(result) {
                resolve(result)
            },
            error: function(err){
                reject(err)
            }
        });
    })
    return ajax
}

success = (message) => {
    toastr.success(message, 'Success !')
}

error = (message) => {
    toastr.error(message, 'Error !')
}

showAlertPopUp = (msg) => {
    swal.fire({
        title: 'Warning',
        text: msg,
        type: 'warning',
        confirmButtonText: 'Yes',
    });
}

toUpper = (data) => {
    return data.charAt(0).toUpperCase() + data.substring(1);
}

