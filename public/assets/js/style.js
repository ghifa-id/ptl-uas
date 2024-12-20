$(document).ready(function() {
    $("#hamburger").click(function() {
        $("#navbarMenu").toggleClass("hidden");
    });

    $("#alert-close").click(function() {
        $("#warning-alert").addClass("hidden");
    });
});