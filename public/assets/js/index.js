// <!-- -------------JAVA-SCRIPT CODE FOR VIEW PASSWORD VERIFICATION--------- -->
  function setCredentialId(id) {
    document.getElementById('credential_id').value = id;
  }

  document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData
      }).then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = data.redirect;
        } else {
          document.getElementById('error-message').textContent = data.message;
          document.getElementById('error-message').style.display = 'block';
        }
      })
      .catch(error => console.error('Error:', error));
  });
