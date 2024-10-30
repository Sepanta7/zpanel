document.getElementById('createUserBtn').onclick = function() {
            document.getElementById('modal').style.display = 'block';
        }

        document.getElementById('remark').oninput = validateFields;
        document.getElementById('duration').oninput = validateFields;
        document.getElementById('volume').oninput = validateFields;
        document.getElementById('configs').oninput = validateFields;

        function validateFields() {
            const remark = document.getElementById('remark').value.trim();
            const duration = document.getElementById('duration').value.trim();
            const volume = document.getElementById('volume').value.trim();
            const configs = document.getElementById('configs').value.trim();

            document.getElementById('submitUser').disabled = !(remark && duration && volume && configs);
        }

        document.getElementById('submitUser').onclick = function() {
            const remark = document.getElementById('remark').value;
            const duration = document.getElementById('duration').value;
            const volume = document.getElementById('volume').value;
            const configs = document.getElementById('configs').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        alert('یوزر جدید با موفقیت ایجاد شد!');
                        document.getElementById('modal').style.display = 'none';
                        location.reload();
                    }
                }
            };
            xhr.send(`action=create_user&remark=${encodeURIComponent(remark)}&duration=${encodeURIComponent(duration)}&volume=${encodeURIComponent(volume)}&configs=${encodeURIComponent(configs)}`);
        }

        function toggleDropdown(event) {
            const dropdown = event.target.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function deleteUser(remark) {
            if (confirm('آیا از حذف این سرویس مطمئن هستید؟')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('سرویس با موفقیت حذف شد!');
                            location.reload();
                        }
                    }
                };
                xhr.send(`action=delete_user&remark=${encodeURIComponent(remark)}`);
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-actions button')) {
                const dropdowns = document.getElementsByClassName("dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].style.display = "none";
                }
            }
        }
