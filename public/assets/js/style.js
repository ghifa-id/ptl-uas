$(document).ready(function() {
    $("#hamburger").click(function() {
        $("#navbarMenu").toggleClass("hidden");
    });

    $("#alert-close").click(function() {
        $("#warning-alert").addClass("hidden");
        $("#success-alert").addClass("hidden");
    });

    $("#checkPassword").click(function () {
        const passwordField = $("#password");
        const fieldType = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", fieldType);
        $(this).toggleClass("fa-eye-slash");
    });
});