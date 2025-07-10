

//get the token to localstorage
const token = localStorage.getItem("token");
 

//logout
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (!confirm("Are you sure you want to logout?")) return;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "http://127.0.0.1:8000/api/logout", true);
    xhr.setRequestHeader("Authorization", "Bearer " + token);
    xhr.setRequestHeader("Accept", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                localStorage.removeItem("token");
                alert("Logout successful");
                window.location.href = "/api/login";
            } else {
                alert("Logout failed");
            }
        }
    };
    xhr.send();
});

// roles  menu  permission url 
const roleUrl = "http://127.0.0.1:8000/api/roleList";
const menuUrl = "http://127.0.0.1:8000/api/menuList";
const permissionurl = "http://127.0.0.1:8000/api/MenuPermissionList";

//role modal
function openRoleModal() {
    document.getElementById("headding").innerText = "Role Table";
    document.getElementById("dataModal").style.display = "block";

    dataList(roleUrl, createRoleTable);
}
// menu modal
function openMenuModal() {
    document.getElementById("headding").innerText = "Menu Table";
    document.getElementById("dataModal").style.display = "block";

    dataList(menuUrl, createMenuTable);
}

//data list for menu and role 
function dataList(apiUrl, data) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", apiUrl, true);
    xhr.setRequestHeader("Accept", "application/json");

    const token = localStorage.getItem("token");
    if (token) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
    }

    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status && Array.isArray(response.data)) {
                data(response.data);
            } else {
                alert("No data found.");
            }
        } else {
            alert("Failed to load data.");
        }
    };

    xhr.send();
}
//create role list
function createRoleTable(items) {
    const tbody = document.getElementById("modalTableBody");
    tbody.innerHTML = '';
    items.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${item.id}</td>
        <td>${item.name}</td>
        <td>
          <button class=" edite" onclick="editRole(${item.id}, '${item.name}')"> <i class='fas fa-edit' title='Edit'></i></button>
          <button class="delete" onclick="deleteRole(${item.id})"><i class='fas fa-trash' title='Delete'></i></button>
        </td>
      `;
        tbody.appendChild(row);
    });
}
//create menu list
function createMenuTable(items) {
    const tbody = document.getElementById("modalTableBody");
    tbody.innerHTML = '';
    items.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td>${item.id}</td>
        <td>${item.name}</td>
        <td>
          <button class=" edite" onclick="editMenu(${item.id}, '${item.name}')"> <i class='fas fa-edit' title='Edit'></i></button>
          <button class="delete" onclick="deleteMenu(${item.id})"><i class='fas fa-trash' title='Delete'></i></button>
        </td>
      `;
        tbody.appendChild(row);
    });
}
//edit role
function editRole(id, currentName) {
    const newName = prompt("Edit role name:", currentName);

    if (newName && newName !== currentName) {
        const xhr = new XMLHttpRequest();

        xhr.open("PUT", `${roleUrl}/${id}`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const token = localStorage.getItem("token");
        if (token) xhr.setRequestHeader("Authorization", "Bearer " + token);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert("Role updated.");
                openRoleModal();
            } else {
                alert("Failed to update role.");
            }
        };

        xhr.send(JSON.stringify({ name: newName }));
    }
}
//delete role
function deleteRole(id) {
    if (confirm("Delete this role?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `${roleUrl}/${id}`, true);

        const token = localStorage.getItem("token");
        if (token) xhr.setRequestHeader("Authorization", "Bearer " + token);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert("Role deleted.");
                openRoleModal();
            } else {
                alert("Failed to delete role.");
            }
        };

        xhr.send();
    }
}
//edit menu
function editMenu(id, currentName) {
    const newName = prompt("Edit menu name:", currentName);
    if (newName && newName !== currentName) {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", `${menuUrl}/${id}`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const token = localStorage.getItem("token");
        if (token) xhr.setRequestHeader("Authorization", "Bearer " + token);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert("Menu updated.");
                openMenuModal();
            } else {
                alert("Failed to update menu.");
            }
        };

        xhr.send(JSON.stringify({ name: newName }));
    }
}
//delete menu
function deleteMenu(id) {
    if (confirm("Delete this menu?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `${menuUrl}/${id}`, true);

        const token = localStorage.getItem("token");
        if (token) xhr.setRequestHeader("Authorization", "Bearer " + token);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert("Menu deleted.");
                openMenuModal();
            } else {
                alert("Failed to delete menu.");
            }
        };

        xhr.send();
    }
}
//close datamodal
function closeModaldata() {
    document.getElementById("dataModal").style.display = "none";

}

//permission modal
function openPermissionModal() {
    document.getElementById("headding").innerText = "Permission Table";
    document.getElementById("permissionModal").style.display = "block";

    const xhr = new XMLHttpRequest();
    xhr.open("GET", permissionurl, true);
    xhr.setRequestHeader("Accept", "application/json");

    const token = localStorage.getItem("token");
    if (token) {
        xhr.setRequestHeader("Authorization", "Bearer " + token);
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status && Array.isArray(response.data)) {
                    createPermissionTable(response.data);
                } else {
                    alert("No data found.");
                }
            } else {
                alert("Failed to load permissions.");
            }
        }
    };

    xhr.send();
}
//create permission list
function createPermissionTable(items) {
    const tbody = document.getElementById("modalTablepermissionBody");
    tbody.innerHTML = '';

    items.forEach(item => {
        const row = document.createElement("tr");
        row.innerHTML = `
        <td class="align">${item.id}</td>
        <td class="align">${item.role_id}</td>
        <td class="align">${item.menu_id}</td>
        <td class="align">${item.fullaccess ? "Yes" : "No"}</td>
        <td class="align">${item.viewonly ? "Yes" : "No"}</td>
        <td class="align">${item.hidden ? "Yes" : "No"}</td>
        <td class="align"><button class="delete " onclick="deletePermission(${item.id})"><i class='fas fa-trash' title='Delete'></button></td>
      `;
        tbody.appendChild(row);
    });
}

//delete permission
function deletePermission(id) {
    if (confirm("Are you sure you want to delete this permission?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `${permissionurl}/${id}`, true);

        const token = localStorage.getItem("token");
        if (token) {
            xhr.setRequestHeader("Authorization", "Bearer " + token);
        }

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    alert("Permission deleted successfully.");
                    openPermissionModal();
                } else {
                    alert("Failed to delete permission.");
                }
            }
        };

        xhr.send();
    }
}

//close permission modal
function closeModal() {
    document.getElementById("permissionModal").style.display = "none";
}



