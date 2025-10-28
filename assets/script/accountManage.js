document.addEventListener('DOMContentLoaded', function() {
    const userTableBody = document.getElementById('user-table-body');
    const createAccountBtn = document.getElementById('create-account-btn');
    const accountModal = new bootstrap.Modal(document.getElementById('accountModal'));
    const accountForm = document.getElementById('accountForm');
    const saveAccountBtn = document.getElementById('saveAccount');

    function clearForm() {
        accountForm.reset();
        document.getElementById('userId').value = '';
    }

    createAccountBtn.addEventListener('click', function() {
        clearForm();
        document.getElementById('accountModalLabel').textContent = 'Create New Account';
        accountModal.show();
    });

    saveAccountBtn.addEventListener('click', function() {
        const userId = document.getElementById('userId').value;
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;
        const password = document.getElementById('password').value;

        if (userId) {
            // Edit existing user
            const userIndex = users.findIndex(u => u.id === parseInt(userId));
            if (userIndex !== -1) {
                users[userIndex] = { ...users[userIndex], name, email, role };
            }
        } else {
            // Create new user
            const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
            users.push({ id: newId, name, email, role });
        }

        renderUsers();
        accountModal.hide();
    });

    userTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn')) {
            const userId = e.target.getAttribute('data-id');
            const user = users.find(u => u.id === parseInt(userId));
            if (user) {
                document.getElementById('userId').value = user.id;
                document.getElementById('name').value = user.name;
                document.getElementById('email').value = user.email;
                document.getElementById('role').value = user.role;
                document.getElementById('password').value = ''; // Clear password field for security
                document.getElementById('accountModalLabel').textContent = 'Edit Account';
                accountModal.show();
            }
        } else if (e.target.classList.contains('delete-btn')) {
            const userId = e.target.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this user?')) {
                users = users.filter(u => u.id !== parseInt(userId));
                renderUsers();
            }
        }
    });

    // Initial render
    renderUsers();
});


document.addEventListener('DOMContentLoaded', function() {
    // Handle suffix checkbox
    const hasSuffixCheckbox = document.getElementById('hasSuffix');
    const suffixInput = document.getElementById('suffix');

    hasSuffixCheckbox.addEventListener('change', function() {
        suffixInput.disabled = !this.checked;
        if (!this.checked) {
            suffixInput.value = '';
            suffixInput.required = false;
        } else {
            suffixInput.required = true;
        }
    });

    // Handle image preview
    const profileImageInput = document.getElementById('profile_image');
    const previewImage = document.getElementById('preview_image');

    profileImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (5MB max)
            if (file.size > 50 * 1024 * 1024) {
                alert('File size must be less than 50MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please upload an image file');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});