$(document).ready(function() {
    $("#hamburger").click(function() {
        $("#navbarMenu").toggleClass("hidden");
    });

    $("#alert-close").click(function() {
        $("#error-alert").addClass("hidden");
        $("#warning-alert").addClass("hidden");
        $("#success-alert").addClass("hidden");
    });
    
    setTimeout(function() {
        $("#error-alert").addClass("hidden");
        $("#warning-alert").addClass("hidden");
        $("#success-alert").addClass("hidden");
    }, 3000);

    $("#checkPassword").click(function () {
        const passwordField = $("#password");
        const fieldType = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", fieldType);
        $(this).toggleClass("fa-eye-slash");
    });
});