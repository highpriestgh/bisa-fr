/**
 * Show doctor thumbnail when  it is selected
 */
jQuery(".update-doctor-thumbnail").on("change", function(){
    var imageSizeLimit = 3 * 1024 * 1024;
    var file = document.getElementById('update-doctor-thumbnail').files;
    var fileLength = file.length;

    if (fileLength > 0) {
		if (file[0].size > imageSizeLimit) {
			showWarningMessage("Image cannot be greater than 3MB", "Admin Action");
		}  else {
            jQuery(".update-doctor-thumbnail-label small").html("Photo De Profil Sélectionnée");

            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery(".update-doctor-thumbnail-res").attr('src', e.target.result);
            }

            reader.readAsDataURL(file[0]);
		}
	}
});

// doctor profile photo setting form
jQuery(".doctor-profile-photo-form").on("submit", function(event) {
    event.preventDefault();

    var thumbnail = document.getElementById('update-doctor-thumbnail').files;
    if (thumbnail.length < 1) {
        showMessage("Admin Action", "Please select thumbnail", "warning");
    } else {
        jQuery(".profile-photo-reset-btn").html("Mise à jour...").prop("disabled", true);
        var formData = new FormData();
        formData.append('_token', jQuery('meta[name=csrf-token]').attr('content'))
        formData.append('doctorThumbnail', thumbnail[0]);

        jQuery.ajax({
            url: CONSTANTS.RESET_DOCTOR_PROFILE_PHOTO_URL,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader ("Authorization", "Bearer ")
            },
            success: function(response) {
                jQuery(".profile-photo-reset-btn").html("Réactualiser").prop("disabled", false);
                if (response.success) {
                	showMessage("Admin Action", response.message, "success");
                } else {
                	showMessage('Admin action',response.message, 'error');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jQuery(".profile-photo-reset-btn").html("Réactualiser").prop("disabled", false);
               console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    }
});

// doctor personnal info setting form
jQuery(".doctor-personal-details-form").on("submit", function(event) {
    event.preventDefault();

    var firstName = jQuery(".first-name").val().trim(),
        lastName = jQuery(".last-name").val().trim(),
        username = jQuery(".username").val().trim(),
        email = jQuery(".email").val().trim(),
        phone = jQuery(".phone").val().trim(),
        address = jQuery(".address").val().trim(),
        bio = jQuery(".bio").val().trim();

    if (firstName == "") {
        showMessage("Account Settings", "PS'il vous plaît entrer le prénom","warning");
    } else if (lastName == "") {
        showMessage("Account Settings", "S'il vous plaît entrer le nom de famille","warning");
    } else if (username == "") {
        showMessage("Account Settings", "Veuillez entrer votre nom d'utilisateur","warning");
    } else if (email == "") {
        showMessage("Account Settings", "S'il vous plaît entrer email","warning");
    } else{
        jQuery(".personnal-info-reset-btn").html("Mise à jour...").prop("disabled", true);
        if (phone == "") {
            phone = "n/a";
        }

        if (address == "") {
            address = "n/a";
        }

        if (bio == "") {
            bio = "n/a";
        }

        var data = {
            _token: jQuery('meta[name=csrf-token]').attr('content'),
            firstName: firstName,
            lastName: lastName,
            username: username,
            email: email,
            phone: phone,
            address: address,
            bio: bio
        }

        jQuery.ajax({
            url: CONSTANTS.RESET_DOCTOR_PERSONAL_INFO_URL,
            type: 'POST',
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader ("Authorization", "Bearer ")
            },
            success: function(response) {                
                jQuery(".personnal-info-reset-btn").html("Réactualiser").prop("disabled", false);
                if (response.success) {
                    showMessage("Account Settings", response.message, "success")
                } else {
                    showMessage('Account Settings',response.message, 'error');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jQuery(".personnal-info-reset-btn").html("Réactualiser").prop("disabled", false);
                console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    }
})

// user password settings form
jQuery(".doctor-password-reset-form").on("submit",function(event) {
    event.preventDefault();

    var currentPassword = jQuery(".current-password").val().trim(),
        newPassword = jQuery(".new-password").val().trim(),
        confirmNewPassword = jQuery(".new-password-conf").val().trim();

    if (currentPassword == "") {
        showMessage("Account Settings", "S'il vous plaît entrer le mot de passe actuel", "warning")
    } else if (newPassword == "") {
        showMessage("Account Settings", "Veuillez entrer un nouveau mot de passe", "warning")
    } else if (confirmNewPassword == "") {
        showMessage("Account Settings", "Veuillez confirmer le nouveau mot de passe", "warning")
    } else if (newPassword.length < 8) {
        showMessage("Account Settings", "Le nouveau mot de passe doit contenir au moins huit caractères.", "warning")
    } else if (newPassword != confirmNewPassword) {
        showMessage("Account Settings", "Le nouveau mot de passe ne correspond pas au mot de passe de confirmation", "warning")
    } else {
        jQuery(".password-reset-btn").html("Mise à jour...").prop("disabled", true);
        var data = {
            _token: jQuery('meta[name=csrf-token]').attr('content'),
            currentPassword: currentPassword,
            newPassword: newPassword,
            confirmNewPassword: confirmNewPassword
        }

        jQuery.ajax({
            url: CONSTANTS.RESET_DOCTOR_PASSWORD_URL,
            type: 'POST',
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader ("Authorization", "Bearer ")
            },
            success: function(response) {
                jQuery(".password-reset-btn").html("Réactualiser").prop("disabled", false);
                if (response.success) {
                	showMessage("Account Settings", response.message, "success")
                	setTimeout(function() {
                        jQuery(".user-password-reset-form").trigger('reset');
                    }, 2000);
                } else {
                	showMessage('Account Settings',response.message, 'error');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                jQuery(".password-reset-btn").html("Réactualiser").prop("disabled", false);
                console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    }
})