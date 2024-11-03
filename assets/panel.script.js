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

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'action': 'create_user',
                    'remark': remark,
                    'duration': duration,
                    'volume': volume,
                    'configs': configs
                })
            }).then(response => response.json()).then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            });
        }

        function showDropdown(event, remark) {
            event.stopPropagation();
            const dropdown = document.getElementById(`dropdown-${remark}`);
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function deleteUser(remark) {
            if (confirm('آیا از حذف این سرویس اطمینان دارید؟')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'delete_user',
                        'remark': remark
                    })
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    }
                });
            }
        }

        function editUser(remark, duration, volume, configs) {
            document.getElementById('remark').value = remark;
            document.getElementById('duration').value = duration;
            document.getElementById('volume').value = volume;
            document.getElementById('configs').value = configs;

            document.getElementById('submitUser').innerText = 'تغییر یوزر';
            document.getElementById('submitUser').onclick = function() {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'action': 'edit_user',
                        'remark': remark,
                        'duration': document.getElementById('duration').value,
                        'volume': document.getElementById('volume').value,
                        'configs': document.getElementById('configs').value
                    })
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    }
                });
            }

            document.getElementById('modal').style.display = 'block';
        }

        window.onclick = function(event) {
            const dropdowns = document.getElementsByClassName('dropdown');
            for (let i = 0; i < dropdowns.length; i++) {
                dropdowns[i].style.display = 'none';
            }

            if (event.target === document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        }
