@extends('layouts.com-dash')

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* ---- state ---- */
    const rowsPerPage = 10;
    let currentPage = 1;
    let notifications = [];
    let municipalities = []; 
    let originalMunicipalities = []; // <-- keep original ones
    let editingRow = null;

    /* ---- DOM refs ---- */
    const tbody = document.querySelector('#companyTable tbody');
    const paginationEl = document.getElementById('pagination');
    const addBtn = document.getElementById('addBtn');
    const notificationBtn = document.getElementById('notificationBtn');
    const addCompanyForm = document.getElementById('addCompanyForm');
    const editCompanyForm = document.getElementById('editCompanyForm');

    /* ---- Pagination ---- */
    function getAllRows() {
        return Array.from(tbody.querySelectorAll('tr'));
    }
    function getVisibleRows() {
        return getAllRows().filter(r => r.style.display !== 'none');
    }
    function displayRows(page = 1) {
        const visible = getVisibleRows();
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        getAllRows().forEach(r => r.style.display = 'none');
        visible.forEach((r, i) => { if (i >= start && i < end) r.style.display = ''; });
        currentPage = page;
        setupPagination();
    }
    function setupPagination() {
        const visible = getVisibleRows();
        const pageCount = Math.max(1, Math.ceil(visible.length / rowsPerPage));
        paginationEl.innerHTML = '';
        const prev = document.createElement('button');
        prev.innerText = '<';
        prev.disabled = (currentPage === 1);
        prev.addEventListener('click', () => displayRows(currentPage - 1));
        paginationEl.appendChild(prev);
        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            if (i === currentPage) btn.classList.add('active');
            btn.addEventListener('click', () => displayRows(i));
            paginationEl.appendChild(btn);
        }
        const next = document.createElement('button');
        next.innerText = '>';
        next.disabled = (currentPage === pageCount);
        next.addEventListener('click', () => displayRows(currentPage + 1));
        paginationEl.appendChild(next);
    }

    /* ---- Modal helpers ---- */
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    /* ---- Notifications ---- */
    function addNotification(action, companyName, details = "") {
        const timestamp = new Date().toLocaleString();
        const msg = `${action} - ${companyName} (${timestamp}) ${details ? "- " + details : ""}`;
        notifications.unshift(msg);
        updateNotifications();
    }
    function updateNotifications() {
        const list = document.getElementById('notificationList');
        list.innerHTML = notifications.map(n => `<li style="margin-bottom:6px;">${n}</li>`).join('');
        document.getElementById('notifBadge').innerText = notifications.length;
    }
    function toggleNotificationsPanel() {
        document.getElementById('notificationPanel').classList.toggle('hidden');
    }

    /* ---- Add Company ---- */
    addCompanyForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateAddCompany()) return;
        const company = {
            name: document.getElementById('addCompanyName').value.trim(),
            email: document.getElementById('addCompanyEmail').value.trim(),
            address: document.getElementById('addCompanyAddress').value.trim(),
            owner: document.getElementById('addCompanyOwner').value.trim(),
            number: document.getElementById('addOwnerNumber').value.trim(),
            municipality: document.getElementById('addMunicipality').value
        };
        addCompanyToTable(company);
        addNotification('Added company', company.name, `Municipality: ${company.municipality}`);
        showSuccess('addAlert', 'addCompanyModal', addCompanyForm);
    });

    function addCompanyToTable(company) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${escapeHtml(company.name)}</td>
            <td>${escapeHtml(company.email)}</td>
            <td>${escapeHtml(company.address)}</td>
            <td>${escapeHtml(company.owner)}</td>
            <td>${escapeHtml(company.number)}</td>
            <td>${escapeHtml(company.municipality)}</td>
            <td>
                <button class="edit"><i class="fa-regular fa-pen-to-square"></i></button>
                <button class="archive"><i class="fa-solid fa-box-archive"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        displayRows(currentPage);
    }

    /* ---- Edit Company ---- */
    function openEditModal(btn) {
    editingRow = btn.closest('tr');
    if (!editingRow) return;

    document.getElementById('editCompanyName').value = editingRow.cells[0].innerText;
    document.getElementById('editCompanyEmail').value = editingRow.cells[1].innerText;
    document.getElementById('editCompanyAddress').value = editingRow.cells[2].innerText;
    document.getElementById('editCompanyOwner').value = editingRow.cells[3].innerText;
    document.getElementById('editOwnerNumber').value = editingRow.cells[4].innerText;

    originalMunicipalities = editingRow.cells[5].innerText.split(',')
        .map(s => s.trim())
        .filter(Boolean);

    municipalities = [...originalMunicipalities]; // working copy
    renderMunicipalities();

    openModal('editCompanyModal');

    }
    editCompanyForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateEditCompany() || !editingRow) return;

        const oldData = {
            name: editingRow.cells[0].innerText,
            email: editingRow.cells[1].innerText,
            address: editingRow.cells[2].innerText,
            owner: editingRow.cells[3].innerText,
            number: editingRow.cells[4].innerText,
            municipality: editingRow.cells[5].innerText.split(',').map(s => s.trim())
        };
        const newData = {
            name: document.getElementById('editCompanyName').value.trim(),
            email: document.getElementById('editCompanyEmail').value.trim(),
            address: document.getElementById('editCompanyAddress').value.trim(),
            owner: document.getElementById('editCompanyOwner').value.trim(),
            number: document.getElementById('editOwnerNumber').value.trim(),
            municipality: [...municipalities]
        };

        editingRow.cells[0].innerText = newData.name;
        editingRow.cells[1].innerText = newData.email;
        editingRow.cells[2].innerText = newData.address;
        editingRow.cells[3].innerText = newData.owner;
        editingRow.cells[4].innerText = newData.number;
        editingRow.cells[5].innerText = newData.municipality.join(', ');

        let changes = [];
        if (oldData.address !== newData.address) changes.push("changed company address");
        if (oldData.number !== newData.number) changes.push("updated contact number");
        if (JSON.stringify(oldData.municipality) !== JSON.stringify(newData.municipality)) {
            changes.push("updated municipalities");
        }

        addNotification('Updated company', newData.name, changes.join(", "));
        showSuccess('editAlert', 'editCompanyModal', editCompanyForm);
        editingRow = null;
    });

    /* ---- Archive ---- */
    function archiveCompany(btn) {
        const row = btn.closest('tr');
        if (!row) return;
        const name = row.cells[0].innerText;
        row.remove();
        addNotification('Archived company', name, "Moved to archive");
        displayRows(currentPage);
    }

    /* ---- Municipalities ---- */
    function addMunicipality() {
        const input = document.getElementById('editMunicipalityInput');
        const v = input.value.trim();
        if (!v) return;
        if (!municipalities.includes(v)) municipalities.push(v);
        renderMunicipalities();
        input.value = '';
    }
    function renderMunicipalities() {
        const list = document.getElementById('editMunicipalityList');
        list.innerHTML = municipalities.map((m, idx) =>
            `<span class="chip">${escapeHtml(m)} 
                <button style="margin-left:6px;background:none;border:0;cursor:pointer;" onclick="removeMunicipality(${idx})">&times;</button>
            </span>`
        ).join(' ');
    }
    window.removeMunicipality = function (index) {
        municipalities.splice(index, 1);
        renderMunicipalities();
    };
    window.addMunicipality = addMunicipality;

    /* ---- Validation helpers ---- */
    function showError(inputId, message) {
        const input = document.getElementById(inputId);
        let errorEl = input.nextElementSibling;
        if (!errorEl || !errorEl.classList.contains("error-msg")) {
            errorEl = document.createElement("small");
            errorEl.classList.add("error-msg");
            errorEl.style.color = "red"; // 🔴 error in red
            input.insertAdjacentElement("afterend", errorEl);
        }
        errorEl.innerText = message;
    }
    function clearError(inputId) {
        const input = document.getElementById(inputId);
        const errorEl = input.nextElementSibling;
        if (errorEl && errorEl.classList.contains("error-msg")) errorEl.innerText = "";
    }

    /* ---- Validators ---- */
    const addValidators = {
        addCompanyName: () => {
            const v = document.getElementById('addCompanyName').value.trim();
            if (!v) showError("addCompanyName", "Company name is required");
            else clearError("addCompanyName");
        },
        addCompanyEmail: () => {
            const v = document.getElementById('addCompanyEmail').value.trim();
            if (!/^[^ ]+@[^ ]+\.[a-z]{2,}$/i.test(v))
                showError("addCompanyEmail", "Enter a valid email address");
            else clearError("addCompanyEmail");
        },
        addCompanyAddress: () => {
            const v = document.getElementById('addCompanyAddress').value.trim();
            if (!v) showError("addCompanyAddress", "Address is required");
            else clearError("addCompanyAddress");
        },
        addCompanyOwner: () => {
            const v = document.getElementById('addCompanyOwner').value.trim();
            if (!v) showError("addCompanyOwner", "Owner is required");
            else clearError("addCompanyOwner");
        },
        addOwnerNumber: () => {
            const v = document.getElementById('addOwnerNumber').value.trim();
            if (!/^[0-9]{11}$/.test(v))
                showError("addOwnerNumber", "Company number must be exactly 11 digits");
            else clearError("addOwnerNumber");
        },
        addMunicipality: () => {
            const v = document.getElementById('addMunicipality').value;
            if (!v) showError("addMunicipality", "Select a municipality");
            else clearError("addMunicipality");
        }
    };

    const editValidators = {
        editCompanyName: () => {
            const v = document.getElementById('editCompanyName').value.trim();
            if (!v) showError("editCompanyName", "Company name is required");
            else clearError("editCompanyName");
        },
        editCompanyEmail: () => {
            const v = document.getElementById('editCompanyEmail').value.trim();
            if (!/^[^ ]+@[^ ]+\.[a-z]{2,}$/i.test(v))
                showError("editCompanyEmail", "Enter a valid email address");
            else clearError("editCompanyEmail");
        },
        editCompanyAddress: () => {
            const v = document.getElementById('editCompanyAddress').value.trim();
            if (!v) showError("editCompanyAddress", "Address is required");
            else clearError("editCompanyAddress");
        },
        editCompanyOwner: () => {
            const v = document.getElementById('editCompanyOwner').value.trim();
            if (!v) showError("editCompanyOwner", "Owner is required");
            else clearError("editCompanyOwner");
        },
        editOwnerNumber: () => {
            const v = document.getElementById('editOwnerNumber').value.trim();
            if (!/^[0-9]{11}$/.test(v))
                showError("editOwnerNumber", "Company number must be exactly 11 digits");
            else clearError("editOwnerNumber");
        },
        editMunicipalityInput: () => {
            if (municipalities.length === 0)
                showError("editMunicipalityInput", "Add at least one municipality");
            else clearError("editMunicipalityInput");
        }
    };

    function attachFieldValidation(validators) {
        Object.keys(validators).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field) return;
            field.addEventListener('input', () => validators[fieldId]());
            field.addEventListener('blur', () => validators[fieldId]());
        });
    }
    attachFieldValidation(addValidators);
    attachFieldValidation(editValidators);

    /* ---- Only digits in number ---- */
    ["addOwnerNumber", "editOwnerNumber"].forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;
        input.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "");
            if (this.value.length > 11) this.value = this.value.slice(0, 11);
        });
    });

    /* ---- Form Validation ---- */
    function validateAddCompany() {
        Object.values(addValidators).forEach(v => v());
        return [...document.querySelectorAll('#addCompanyForm .error-msg')].every(e => e.innerText === "");
    }
    function validateEditCompany() {
        Object.values(editValidators).forEach(v => v());
        return [...document.querySelectorAll('#editCompanyForm .error-msg')].every(e => e.innerText === "");
    }

    /* ---- Success ---- */
    function showSuccess(alertId, modalId, form) {
        document.getElementById(alertId).style.display = 'block';
        setTimeout(() => {
            document.getElementById(alertId).style.display = 'none';
            closeModal(modalId);
            form.reset();
            displayRows(currentPage);
        }, 1000);
    }

    /* ---- Delegation ---- */
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.edit');
        if (editBtn) { e.preventDefault(); openEditModal(editBtn); return; }
        const archiveBtn = e.target.closest('.archive');
        if (archiveBtn) { e.preventDefault(); if (confirm('Archive this company?')) archiveCompany(archiveBtn); return; }
    });

    /* ---- Wire ---- */
    addBtn.addEventListener('click', () => openModal('addCompanyModal'));
    notificationBtn.addEventListener('click', toggleNotificationsPanel);

    /* ---- Close buttons ---- */
    document.querySelectorAll('.modal .close, .modal .btn-cancel').forEach(btn => {
        btn.addEventListener('click', function () {
            const modal = this.closest('.modal');
            if (modal) modal.style.display = 'none';
        });
    });

    /* ---- Close on backdrop ---- */
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    });

    displayRows(1);
});

/* ---- Escape HTML ---- */
function escapeHtml(s) {
    return s ? s.replace(/&/g, "&amp;").replace(/</g, "&lt;")
        .replace(/>/g, "&gt;").replace(/"/g, "&quot;") : '';
}
/* ---- Clear municipalities ---- */
window.clearMunicipalities = function () {
    municipalities = [...originalMunicipalities]; // reset back to original only
    renderMunicipalities();
    document.getElementById('editMunicipalityInput').value = '';
};


</script>
@endsection



    @section('content')

        <!--MAIN CONTENT-->
        <div class="main-content">
            <div class="header-wrapper">
                <div class="topnav">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search Company or Email...">
                    </div>
                    <div class="right-section">
                        <!-- Notifications -->
                        <div class="notification-btn" id="notificationBtn" title="Notifications">
                            <i class="fa-solid fa-bell"></i>
                            <span id="notifBadge" class="badge">0</span>
                        </div>
                        <div class="add-btn" id="addBtn">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Company</span>
                        </div>
                    </div>

                    <!-- NOTIFICATIONS PANEL -->
                    <div id="notificationPanel" class="notification-panel hidden">
                        <h4 style="margin-bottom:8px;">Notifications</h4>
                         <ul id="notificationList" style="list-style:none; padding-left:0; margin:0;"></ul>
                    </div>
                </div>
                <!-- FILTER CHIPS -->
                    <div class="filter-bar">
                        <span style="font-weight:600;">Filter by:</span>
                        {{-- <button class="filter-chip" onclick="filterBy('company')"><i class="fa-regular fa-building"></i> Company</button> --}}
                        <button class="filter-chip" id="filterCompanyBtn"><i class="fa-regular fa-building"></i> Company</button>
                        
                        <!-- municipality quick filter -->
                        <div class="dropdown">
                            <button class="filter-chip"><i class="fa-solid fa-building-columns"></i> Municipality ▾</button>
                            <div class="dropdown-content">
                                <button onclick="applyMunicipalityFilter('Cebu')">Cebu</button>
                                <button onclick="applyMunicipalityFilter('Lapu-Lapu')">Lapu-Lapu</button>
                                <button onclick="applyMunicipalityFilter('Mandaue')">Mandaue</button>
                                <button onclick="resetFilters()">Show all</button>
                            </div>
                        </div>
                       <!-- modified -->
                        <div class="dropdown">
                            <button class="filter-chip"><i class="fa-regular fa-calendar"></i> Modified ▾</button>
                            <div class="dropdown-content">
                                <button onclick="filterByDate('today')">Today</button>
                                <button onclick="filterByDate('7')">Last 7 days</button>
                                <button onclick="filterByDate('30')">Last 30 days</button>
                                <button onclick="filterByDate('2025')">This Year (2025)</button>
                                <button onclick="filterByDate('2024')">Last Year (2024)</button>
                                <button onclick="customDateRange()">Custom Range</button>
                            </div>
                        </div>
                    </div>
                 <!--TABLE CONTAINER-->
                <div class="tabular-wrapper">
                    <div class="tabular-container">
                        <table id="companyTable">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Owner</th>
                                    <th>Contact</th>
                                    <th>Municipality</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Swiss Sense Inc.</td>
                                    <td>swisssense@gmail.com</td>
                                    <td>Cebu City</td>
                                    <td>Charles Reganion</td>
                                    <td>09123456789</td>
                                    <td>Tabuelan</td>
                                    <td>
                                        <button class="edit" onclick="openEditModal(this)"><i class="fa-regular fa-pen-to-square"></i></button>
                                        <button class="archive" onclick="archiveCompany(this)"><i class="fa-solid fa-box-archive"></i></button>
                                        </td>
                                    </tr>
                                    
                            </tbody>
                        </table>

                        <!-- PAGINATION -->
                        <div id="pagination" style="margin-top: 15px; text-align: center; font-size: 27px"></div>

                        <!-- ADD COMPANY MODAL -->
                        <div class="modal" id="addCompanyModal">
                            <div class="modal-content">
                                <span class="modal-close" onclick="closeModal('addCompanyModal')">&times;</span>
                                <h2>Add Company</h2>
                                <form id="addCompanyForm">
                                    <label>Company Name</label>
                                    <input type="text" id="addCompanyName" placeholder="Company Name">
                                    <small class="error-msg"></small>

                                    <label>Company Email</label>
                                    <input type="email" id="addCompanyEmail" placeholder="Company Email">
                                    <small class="error-msg"></small>

                                    <label>Company Address</label>
                                    <input type="text" id="addCompanyAddress" placeholder="Company Address">
                                    <small class="error-msg"></small>

                                    <label>Company Owner</label>
                                    <input type="text" id="addCompanyOwner" placeholder="Company Owner">
                                    <small class="error-msg"></small>

                                    <label>Owner Number</label>
                                    <input type="text" id="addOwnerNumber" placeholder="Owner Number">
                                    <small class="error-msg"></small>

                                    <label>Municipality</label>
                                    <select id="addMunicipality">
                                        <option value="">Select Municipality</option>
                                        <option value="Cebu">Cebu</option>
                                        <option value="Lapu-Lapu">Lapu-Lapu</option>
                                        <option value="Mandaue">Mandaue</option>
                                    </select>
                                    <small class="error-msg"></small>

                                    <div class="actions" style="margin-top:12px; text-align:right;">
                                        <button type="button" class="btn-cancel" onclick="closeModal('addCompanyModal')">Cancel</button>
                                        <button type="submit" class="btn-add">Add Company</button>
                                    </div>
                        </form>
                                <div class="alert" id="addAlert">Company added successfully!</div>
                            </div>
                        </div>


                        <!-- EDIT COMPANY MODAL -->
                        <div class="modal" id="editCompanyModal">
                            <div class="modal-content">
                                <span class="modal-close" onclick="closeModal('editCompanyModal')">&times;</span>
                                <h2>Edit Company</h2>
                                <form id="editCompanyForm">
                                    <label>Company Name</label>
                                    <input type="text" id="editCompanyName" placeholder="Company Name">
                                    <span class="error" id="errorEditName"></span>

                                    <label>Company Email</label>
                                    <input type="email" id="editCompanyEmail" placeholder="Company Email">
                                    <span class="error" id="errorEditEmail"></span>

                                    <label>Company Address</label>
                                    <input type="text" id="editCompanyAddress" placeholder="Company Address">
                                    <span class="error" id="errorEditAddress"></span>

                                    <label>Company Owner</label>
                                    <input type="text" id="editCompanyOwner" placeholder="Company Owner">
                                    <span class="error" id="errorEditOwner"></span>

                                    <label>Owner Number</label>
                                    <input type="text" id="editOwnerNumber" placeholder="Owner Number">
                                    <span class="error" id="errorEditNumber"></span>

                                    <label>Municipality</label>
                                    <div id="editMunicipalityWrapper" style="display:flex; gap:8px; align-items:center;">
                                        <select id="editMunicipalityInput">
                                            <option value="">Select Municipality</option>
                                            <option value="Cebu">Cebu</option>
                                            <option value="Lapu-Lapu">Lapu-Lapu</option>
                                            <option value="Mandaue">Mandaue</option>
                                        </select>
                                        <button type="button" onclick="addMunicipality()" class="btn-add">+</button>
                                        <button type="button" onclick="clearMunicipalities()" class="btn-cancel">Clear</button>
                                    </div>
                                    <div id="editMunicipalityList" style="margin-top:8px;"></div>

                                    <div class="actions" style="margin-top:12px; text-align:right;">
                                        <button type="button" class="btn-cancel" onclick="closeModal('editCompanyModal')">Cancel</button>
                                        <button type="submit" class="btn-save">Save changes</button>
                                    </div>
                                </form>
                                <div class="alert" id="editAlert">Changes saved successfully!</div>
                            </div>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>

    @endsection


    @section('styles')
    <!--FONT AWESOME LINK-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

        <!--CSS-->
        <style>
                @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');            
            *{
                margin: 0;
                padding: 0;
                border: none;
                outline: none;
                box-sizing: border-box;
                font-family: "Poppins", sans-serif;
            }
            body {
                display: flex;
            }
            /* ****SIDE BAR**** */
            .sidebar {
                position: sticky;
                top: 0;
                left: 0;
                bottom: 0;
                width: 280px;
                height: 100vh;
                padding: 0 1.7rem;
                color: #5C5C5C;
                overflow: hidden;
                transition: all 0.5s linear;
            }
            /* .sidebar:hover{
                width: 110px;
                transition: 0.5s;
            } */
            .logo {
                margin: 30px auto 50px auto;
                height: 75px;
                padding: 16px;
            }
            .menu {
                height: 88%;
                position: relative;
                list-style: none;
                padding: 0;
            }
            .menu li{
                padding: 1rem;
                margin: 30px 0;
                border-radius: 8px;
                transition: all 0.5s ease-in-out;
            }
            .menu li:hover,
            .menu li.active {
                background: #003D80;
            }

            .menu a {
                color: #5C5C5C;
                font-size: 17px;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }
            .menu li:hover a,
            .menu li.active a{
                color: #fff;
            }
            .menu a span {
                overflow: hidden;
            }
            .menu a i {
                font-size: 1.2rem;
            }
            .logout {
                position: absolute;
                bottom: 50px;
                left: 0;
                width: 100%;
            } 
            .logout a {
                color: #F42C1D !important;
            }
            .logout a:hover {
                color: #fff !important; 
                border-radius: 8px; 
                transition: 0.5s linear ease;
            }

            /* ****MAIN CONTENT**** */
            .main-content{
                position: relative;
                background: #ebe9e9;
                width: 100%;
                padding: 1rem;
            }
            .header-wrapper{
                height: 100%;
                background: #fff;
                border-radius: 20px;
                padding:10px 2rem;
            }
            .topnav{
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                background: #fff;
                border-radius: 10px;
                padding:10px 2rem;
            }
            .right-section {
                position: relative; 
                display: inline-block;   
                top: 20px;   
                padding:20px;  
            }
            .search-box{
                background: #F2F2F2;
                border-radius: 44px;
                color: #535353;
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 4px 12px;
                width: 500px;
                margin: 20px 0 0 0;
            }
            .search-box input{
                background: transparent;
                padding: 10px;
            }
            .search-box i{
                font-size: 1.2rem;
                cursor: pointer;
                transition: all 0.5s ease;
            }
            .search-box i:hover{
                transform: scale(1.2);
            }
            .notification-btn{
                color: #003D80;
                position: absolute;
                top: -10px;  
                right: 40px;
                cursor: pointer;
            }
            .notification-btn i{
                font-size: 1.5rem;
                cursor: pointer;
                transition: all 0.5s ease;
            }
            .notification-btn i:hover{
                transform: scale(1.2);
            }
            .add-btn{
                gap: 1.5rem;
                background: #046EF8;
                color: #fff;
                padding: 1rem;
                margin: 30px 0;
                border-radius: 8px;
            }  
            .badge {
                background: red;
                color: white;
                border-radius: 50%;
                padding: 3px 7px;
                font-size: 10px;
                position: absolute;
                top: -5px;
                right: -20px;
            }

            .notification-panel{ 
                position:absolute; 
                top:60px; 
                right:36px; 
                width:320px; 
                max-height:340px; 
                overflow:auto; 
                background:#fff; 
                box-shadow:0 8px 24px rgba(0,0,0,0.12); 
                border-radius:8px; 
                padding:12px; 
                z-index:50; 
            }
            .hidden { 
                display: none !important;
            }
            .alert{ 
                display:none; 
                margin-top:8px; 
                padding:8px; 
                border-radius:6px; 
                background:#d1e7dd; 
                color:#0f5132; 
            }
            .chip{ 
                display:inline-block; 
                padding:6px 10px; 
                border-radius:16px; 
                background:#f1f1f1; 
                margin:4px; 
                font-size:13px;
            }


            /* **** TABULAR SECTION **** */
            .tabular-wrapper{
                padding: 0 2rem;
                margin-top:0;
            }
            .tabular-container{
                width: 100%;
            }
            table{
                width: 100%;
                border-collapse: separate;
                border-spacing: 0 10px;
            }
            thead{
                background: #003D80;
                color: #fff;
            }
            thead th:first-child {
                border-top-left-radius: 20px;
            }

            thead th:last-child {
                border-top-right-radius: 20px;
            }
            th{
                padding: 15px;
                text-align: left;
            }
            tbody{
                background: #fff;
            }
            tbody tr {
                background: #F5F5F5;  
                border-radius: 10px;
            }
            td{
                padding: 15px;
                font-size: 16px;
                color: #373737;
            }
            .edit, .archive {
                padding: 6px 10px;
                border-radius: 5px;
                cursor: pointer;
                color: #fff;
            }

            .edit {
                background: #FFC107; /* yellow */
            }

            .archive {
                background: #5aac44; /* green */
            }
            #pagination{ 
                text-align:center;
                margin-top:12px; 
            }

            #pagination button {
            margin: 0 5px;
            padding: 5px 12px;
            border-radius: 5px;
            cursor: pointer;
            background: #fff;
            color: #003D80;
            font-size: 16px;
            transition: 0.3s;
        }

        #pagination button.active {
            background: #003D80;
            color: #fff;
        }

        #pagination button.prev {
            background: #EDEDED;
            color: #333;
            border: 1px solid #ccc;
        }

        #pagination button.next {
            background: #003D8045;
            color: #fff;
        }

        #pagination button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ==== MODAL STYLES ==== */
        .modal {
            display: none; /* hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5); /* dark overlay */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            width: 520px;
            max-width: 95%;
            position: relative;
        }
        .modal .modal-close{ 
            position:absolute; 
            top:8px; 
            right:12px; 
            font-size:22px; 
            cursor:pointer; 
        }
        .modal-content h2 {
            margin-bottom: 15px;
            color: #003D80;
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
            font-weight: 500;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .modal-content .actions {
            margin-top: 20px;
            text-align: right;
        }

        .modal-content button {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
        }

        .btn-cancel {
            background: #EDEDED;
            color: #333;
        }

        .btn-save {
            background: #046EF8;
            color: #fff;
        }

        .btn-add {
            background: #046EF8;
            color: #fff;
        }

        .alert {
            display: none;
            margin-top: 15px;
            padding: 10px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 8px;
        }
        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #333;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #F42C1D;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 2px;
            display: block;
        }
        /* Filter chips & dropdown */
        .filter-bar{ 
            margin-top:12px; 
            display:flex; 
            align-items:center; 
            gap:8px; 
            flex-wrap:wrap; 
        }
        .filter-chip{ 
            background:#f1f1f1; 
            border-radius:16px; 
            padding:6px 12px; 
            cursor:pointer; 
            border:0; 
        }
        .filter-chip:hover{ 
            background:#e0e0e0; 
        }
        .dropdown{ 
            position:relative; 
            display:inline-block; 
        }
        .dropdown-content{ 
            display:none; 
            position:absolute; 
            background:#fff; 
            box-shadow:0 2px 8px rgba(0,0,0,0.12); 
            padding:8px; 
            border-radius:8px; 
            z-index:30; 
        }
        .dropdown:hover .dropdown-content{ 
            display:block; 
        }
        .dropdown-content button{ 
            display:block; 
            width:100%; 
            padding:8px; 
            background:transparent; 
            text-align:left; 
            cursor:pointer; 
            border-radius:6px; 
        }
        .filter-chip {
            background: #f1f1f1;
            border: none;
            border-radius: 16px;
            padding: 6px 12px;
            margin: 4px;
            cursor: pointer;
        }
        .filter-chip:hover {
            background: #e0e0e0;
        }
        .modal-content select {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
        }
        .error-msg {
            color: red;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }



        </style>

    @endsection