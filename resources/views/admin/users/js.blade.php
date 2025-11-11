



{{------------------------------------------------------------------------LIBRARY--------------------------}}
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>



{{--------------------------------------------------------------------- CREATE ACCOUNT-------------------------------------------------}}
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


{{----------------------------------------------------------------------USER LIST OF ACCOUNTS--------------------------------------}}

   <script>
      document.addEventListener('DOMContentLoaded', function () {
      const deleteModal = document.getElementById('confirmDeleteModal');
      const togglePasswordBtn = document.getElementById('togglePassword');
      const adminPasswordInput = document.getElementById('adminPassword');
      const passwordError = document.getElementById('passwordError');
      const deleteForm = document.getElementById('deleteUserForm');

      // Show/Hide password upon delete
      togglePasswordBtn.addEventListener('click', function () {
          if (adminPasswordInput.type === 'password') {
              adminPasswordInput.type = 'text';
              this.innerHTML = '<i class="bx bx-hide"></i>';
          } else {
              adminPasswordInput.type = 'password';
              this.innerHTML = '<i class="bx bx-show"></i>';
          }
      });

      // Set form action and username on modal show
      deleteModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const userId = button.getAttribute('data-user-id');
          const username = button.getAttribute('data-username');
          const message = document.getElementById('deleteMessage');

          deleteForm.action = "{{ route('users.destroy', ':id') }}".replace(':id', userId);
          message.textContent = `Are you sure you want to delete the account "${username}"?`;
          passwordError.classList.add('d-none');
          adminPasswordInput.value = '';
      });

      // AJAX form submission
      deleteForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const formData = new FormData(deleteForm);

          fetch(deleteForm.action, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': formData.get('_token'),
                  'X-Requested-With': 'XMLHttpRequest'
              },
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Close modal
                  const bsDeleteModal = bootstrap.Modal.getInstance(deleteModal);
                  bsDeleteModal.hide();

                  // Remove deleted row from table
                  const row = document.querySelector(`button[data-user-id="${data.user_id}"]`).closest('tr');
                  if (row) row.remove();

                  // Show success modal
                  const successModalEl = document.getElementById('successModal');
                  document.getElementById('successModalLabel').textContent = 'Deleted';
                  document.querySelector('#successModal .modal-body').textContent = data.message;
                  const successModal = new bootstrap.Modal(successModalEl);
                  successModal.show();
              } else if (data.error) {
                  passwordError.textContent = data.error;
                  passwordError.classList.remove('d-none');
              }
          })
          .catch(err => {
              console.error(err);
          });
      });
    });
 </script>

 <script>
  //SEARCH BARRRRRRR
    document.addEventListener("DOMContentLoaded", () => {
      const searchInput = document.querySelector('#globalSearch'); // global search bar
      const tableBody = document.querySelector('#logsBody');
      const rows = Array.from(tableBody.querySelectorAll('tr'));
      const paginationWrapper = document.querySelector('#paginationWrapper');
      const rowsPerPage = 20;
      let currentPage = 1;

      // Render table function
      function renderTable(filteredRows) {
        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const visibleRows = filteredRows.slice(start, end);

        tableBody.innerHTML = '';
        visibleRows.forEach(row => tableBody.appendChild(row));

        // Pagination buttons
        paginationWrapper.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
          const btn = document.createElement('button');
          btn.textContent = i;
          btn.className = 'btn btn-sm ' + (i === currentPage ? 'btn-primary' : 'btn-outline-primary');
          btn.addEventListener('click', () => {
            currentPage = i;
            renderTable(filteredRows);
          });
          paginationWrapper.appendChild(btn);
        }
      }

      // Live search filter
      function applySearch() {
        const query = searchInput.value.toLowerCase().trim();
        const filtered = rows.filter(row => row.textContent.toLowerCase().includes(query));
        currentPage = 1; // always start from page 1 when searching
        renderTable(filtered);
      }

      if (searchInput) {
        searchInput.addEventListener('input', applySearch);
      }

      // Initial table
      renderTable(rows);
    });
 </script>








<script>
document.addEventListener("DOMContentLoaded", function () {
  const table = document.querySelector("table");
  const headers = table.querySelectorAll("th");

  // Only these column indexes are sortable (0-based)
  const sortableColumns = [3, 4, 5]; // Role, Date Created, Time

  headers.forEach((header, index) => {
    if (!sortableColumns.includes(index)) return; // Skip non-sortable headers

    header.style.cursor = "pointer"; // Indicate it's clickable

    // Create a single arrow span and append once
    const arrow = document.createElement("span");
    arrow.classList.add("sort-arrow");
    arrow.style.marginLeft = "6px";
    arrow.textContent = "▲"; // default (neutral)
    arrow.style.opacity = "0.3"; // faded look for inactive
    header.appendChild(arrow);

    header.addEventListener("click", () => {
      const rows = Array.from(table.querySelectorAll("tbody tr"));
      const ascending = !header.classList.contains("asc");

      // Clear sorting states from other sortable headers
      headers.forEach(h => {
        if (h !== header && sortableColumns.includes([...headers].indexOf(h))) {
          h.classList.remove("asc", "desc");
          const hArrow = h.querySelector(".sort-arrow");
          if (hArrow) {
            hArrow.textContent = "▲";
            hArrow.style.opacity = "0.3"; // reset faded
          }
        }
      });

      // Toggle direction for clicked header
      header.classList.toggle("asc", ascending);
      header.classList.toggle("desc", !ascending);

      // Sort rows
      rows.sort((a, b) => {
        const aText = a.children[index].innerText.trim();
        const bText = b.children[index].innerText.trim();
        return ascending
          ? aText.localeCompare(bText, undefined, { numeric: true })
          : bText.localeCompare(aText, undefined, { numeric: true });
      });

      // Re-render sorted rows
      const tbody = table.querySelector("tbody");
      tbody.innerHTML = "";
      rows.forEach(row => tbody.appendChild(row));

      // Update arrow for active header
      arrow.textContent = ascending ? "▲" : "▼";
      arrow.style.opacity = "1"; // highlight active arrow
    });
  });
});
</script>
