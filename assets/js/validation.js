$(document).ready(function() 
{
 /*password alphanumeric set*/
 $.validator.addMethod("pwcheckallowedchars", function(value) {
    return /^[a-zA-Z0-9!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]+$/.test(value) // has only allowed chars letter
}, "The password contains non-admitted characters");

$.validator.addMethod("pwcheckspechars", function(value) {
    return /[!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]/.test(value)
}, "The password must contain at least one special character");

$.validator.addMethod("pwcheckconsecchars", function(value) {
    return !(/(.)\1\1/.test(value)) // does not contain 3 consecutive identical chars
}, "The password must not contain 3 consecutive identical characters");

$.validator.addMethod("pwchecklowercase", function(value) {
    return /[a-z]/.test(value) // has a lowercase letter
}, "The password must contain at least one lowercase letter");

$.validator.addMethod("pwcheckrepeatnum", function(value) {
    return /\d{2}/.test(value) // has a lowercase letter
}, "The password must contain at least one lowercase letter");

$.validator.addMethod("pwcheckuppercase", function(value) {
    return /[A-Z]/.test(value) // has an uppercase letter
}, "The password must contain at least one uppercase letter");

$.validator.addMethod("pwchecknumber", function(value) {
    return /\d/.test(value) // has a digit
}, "The password must contain at least one number");

/*Email*/
$.validator.addMethod("emailExt", function(value, element, param) {
    return this.optional(element)||value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
}, 'Your email id is not correct format');

//admin login form validation
$("#adminlogin").validate({
    rules: {
        adminemail: {
            required: true,
            emailExt: true,
            email: true
        },
        adminpassword: {
            required: true
        }
    },
    submitHandler: function(form) 
    {
        form.submit();
    }
});

});