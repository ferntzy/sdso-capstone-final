{{-- <script> MICHAEL --------------------------------------------------

  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});


$(document).on("click", "#btnCreate", function(e){
  e.preventDefault();
  $.ajax({
    url: "{{ route('users.store') }}",
    method: "POST",
    data: $("#frmCreate").serialize(),
    cache: false,

    beforeSend: function() {
      $("#btnCreate").prop("disabled", true).html("Creating account...");
    },

    success: function(response) {
      $("#btnCreate").prop("disabled", false).html("CREATE");

      Swal.fire({
        title: "Success",
        icon: "success",
        text: response.message || "User created successfully"
      }).then(() => {
        // ✅ Clear the form after success
        $("#frmCreate")[0].reset();

        $("#frmCreate input[name='username']").focus();
        window.location.href = "{{ route('users.index') }}";
      });
    },

    error: function(xhr) {
      $("#btnCreate").prop("disabled", false).html("CREATE");
      // Handle Laravel exception messages
      let message = "An unexpected error occurred.";

      if (xhr.responseJSON) {
        if (xhr.responseJSON.error) {
          message = xhr.responseJSON.error; // from catch(Exception $e)
        } else if (xhr.responseJSON.errors) {
          // from validation errors (array)
          message = Object.values(xhr.responseJSON.errors).flat().join("\n");
        } else if (xhr.responseJSON.message) {
          // fallback for generic Laravel JSON responses
          message = xhr.responseJSON.message;
        }
      }

      Swal.fire({
        title: "Error!",
        text: message,
        icon: "error"
      });
    }
  })
})
</script> --}}



{{-- LIIBRARRRYYYYY--}}
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>




<script>
  //para password hide and seek
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function () {
          const input = this.parentElement.querySelector('.password-field');
          const eyeIcon = this.querySelector('i');
          if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
          } else {
            input.type = 'password';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
          }
        });
      });

      // // Live password match check
      // const passwordInput = document.querySelector('input[name="password"]');
      // const confirmInput = document.querySelector('input[name="password_confirmation"]');
      // const errorDiv = document.getElementById('password-match-error');
      // const form = document.querySelector('form');

      // confirmInput.addEventListener('input', function() {
      //   if (confirmInput.value === '') {
      //     errorDiv.style.display = 'none';
      //     return;
      //   }
      //   if (confirmInput.value !== passwordInput.value) {
      //     errorDiv.style.display = 'block';
      //   } else {
      //     errorDiv.style.display = 'none';
      //   }
      // });

      // // Prevent form submission if passwords do not match
      // form.addEventListener('submit', function(e) {
      //   if (confirmInput.value !== passwordInput.value) {
      //     e.preventDefault();
      //     confirmInput.focus();
      //   }
      // });
    });
</script>

<script>
  // lex sweet alert check all-----------------------------------------------------------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.querySelector('input[name="password"]');
    const passwordConfirmInput = document.querySelector('input[name="password_confirmation"]');
    const usernameError = document.getElementById('username-error');
    const emailError = document.getElementById('email-error');
    const passwordMatchError = document.getElementById('password-match-error');
    const form = document.querySelector('form');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let usernameTaken = false;
    let emailTaken = false;

    // ✅ Debounce for smooth checking
    let timer;
    function debounceCheck(field, value) {
      clearTimeout(timer);
      timer = setTimeout(() => checkAvailability(field, value), 400);
    }

    // ✅ Check username/email availability
    async function checkAvailability(field, value) {
      if (!value.trim()) return;

      const response = await fetch("{{ route('users.checkAvailability') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrf,
        },
        body: JSON.stringify({ field, value }),
      });

      const data = await response.json();
      if (field === "username") {
        usernameTaken = !data.available;
        if (usernameTaken) {
          usernameError.style.display = "block";
          usernameInput.classList.add("is-invalid");
        } else {
          usernameError.style.display = "none";
          usernameInput.classList.remove("is-invalid");
        }
      } else if (field === "email") {
        emailTaken = !data.available;
        if (emailTaken) {
          emailError.style.display = "block";
          emailInput.classList.add("is-invalid");
        } else {
          emailError.style.display = "none";
          emailInput.classList.remove("is-invalid");
        }
      }
    }

    usernameInput.addEventListener('input', e => debounceCheck('username', e.target.value));
    emailInput.addEventListener('input', e => debounceCheck('email', e.target.value));

    // ✅ Password match live check
    function checkPasswords() {
      if (passwordInput.value && passwordConfirmInput.value && passwordInput.value !== passwordConfirmInput.value) {
        passwordMatchError.style.display = "block";
        passwordConfirmInput.classList.add("is-invalid");
        return false;
      } else {
        passwordMatchError.style.display = "none";
        passwordConfirmInput.classList.remove("is-invalid");
        return true;
      }
    }

    passwordInput.addEventListener('input', checkPasswords);
    passwordConfirmInput.addEventListener('input', checkPasswords);

    // ✅ When submitting
    form.addEventListener('submit', async function (e) {
      e.preventDefault(); // stop default first

      const passwordsMatch = checkPasswords();
      const passwordTooShort = passwordInput.value.length < 6;

      // 🔍 Check all validation conditions
      if (usernameTaken || emailTaken || !passwordsMatch || passwordTooShort) {
        let message = '';

        if (usernameTaken && emailTaken) {
          message = 'Both username and email are already used!';
        } else if (usernameTaken) {
          message = 'Username is already used!';
        } else if (emailTaken) {
          message = 'Email is already used!';
        } else if (passwordTooShort) {
          message = 'Password must be at least 6 characters long!';
        } else if (!passwordsMatch) {
          message = 'Passwords do not match!';
        }

        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: message,
          confirmButtonColor: '#d33',
        });

        return false; // stop submit
      }


     // ✅ Passed all checks — show loading-----------------------------------------------------------------------------
    Swal.fire({
      title: 'Creating Account...',
      text: 'Please wait while we process.',
      icon: 'info',
      showConfirmButton: false,
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    // ✅ Send the form via fetch instead of reloading
    const formData = new FormData(form);

    fetch(form.action, {
      method: form.method || 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
      },
      body: formData,
    })
      .then(async response => {
        const data = await response.json().catch(() => ({}));

        Swal.close(); // close loading spinner when response arrives

        if (response.ok) {
          // 🎉 Success Alert
          Swal.fire({
            icon: 'success',
            title: 'Account Created!',
            text: data.message || 'The new user has been successfully created.',
            confirmButtonColor: '#3085d6',
          }).then(() => {
            // ✅ Redirect after confirmation
            window.location.href = 'http://127.0.0.1:8000/admin/users';
          });
        } else {
          // ❌ Server returned error
          Swal.fire({
            icon: 'error',
            title: 'Something went wrong!',
            text: data.message || 'Please check your inputs or try again later.',
            confirmButtonColor: '#d33',
          });
        }
      })
      .catch(error => {
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'Network Error',
          text: 'Please check your internet connection and try again.',
          confirmButtonColor: '#d33',
        });
        console.error('Submit error:', error);
      });

        });
      });
</script>
