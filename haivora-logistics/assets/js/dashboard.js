/**
 * Customer Dashboard Client Controller
 * Phase 5: Customer Accounts and Dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    let currentUser = null;
    let customerShipments = [];

    // DOM Elements
    const alertBar = document.getElementById('dash-alert-bar');
    const tabBtns = document.querySelectorAll('.dash-tab-btn');
    const viewPanes = document.querySelectorAll('.dash-view-pane');
    const btnLogout = document.getElementById('btn-dashboard-logout');

    function showAlert(msg, isSuccess = false) {
        if (!alertBar) return;
        alertBar.style.display = 'block';
        if (isSuccess) {
            alertBar.style.backgroundColor = '#D1FAE5';
            alertBar.style.color = '#047857';
            alertBar.style.border = '1px solid #10B981';
        } else {
            alertBar.style.backgroundColor = '#FEE2E2';
            alertBar.style.color = '#B91C1C';
            alertBar.style.border = '1px solid #F87171';
        }
        alertBar.textContent = msg;
        setTimeout(() => { alertBar.style.display = 'none'; }, 5000);
    }

    // Tab Switcher
    function switchTab(tabId) {
        tabBtns.forEach(btn => {
            if (btn.getAttribute('data-tab') === tabId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        viewPanes.forEach(pane => {
            if (pane.id === 'tab-view-' + tabId) {
                pane.classList.add('active');
            } else {
                pane.classList.remove('active');
            }
        });
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    document.querySelectorAll('.btn-link-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            if (target) switchTab(target);
        });
    });

    // Load User Data
    async function initDashboard() {
        try {
            const res = await fetch('/api/customer/me');
            if (!res.ok) {
                window.location.href = '/login/';
                return;
            }

            currentUser = await res.json();
            renderUserProfile(currentUser);
            loadCustomerShipments();
            renderNotificationsFeed(currentUser.notifications || []);
        } catch (err) {
            console.error('Failed to authenticate session:', err);
            window.location.href = '/login/';
        }
    }

    function renderUserProfile(user) {
        const nameSpan = document.getElementById('user-display-name');
        const emailSpan = document.getElementById('user-email-badge');
        const avatarBox = document.getElementById('user-avatar');

        if (nameSpan) nameSpan.textContent = user.display_name || user.first_name || 'Valued Customer';
        if (emailSpan) emailSpan.textContent = user.email;
        if (avatarBox) {
            const init1 = (user.first_name || 'C').charAt(0).toUpperCase();
            const init2 = (user.last_name || 'U').charAt(0).toUpperCase();
            avatarBox.textContent = init1 + init2;
        }

        // Profile Form Fields
        const fFirst = document.getElementById('prof-firstname');
        const fLast = document.getElementById('prof-lastname');
        const fEmail = document.getElementById('prof-email');
        const fPhone = document.getElementById('prof-phone');
        const fCompany = document.getElementById('prof-company');

        if (fFirst) fFirst.value = user.first_name || '';
        if (fLast) fLast.value = user.last_name || '';
        if (fEmail) fEmail.value = user.email || '';
        if (fPhone) fPhone.value = user.phone || '';
        if (fCompany) fCompany.value = user.company || '';

        // Notification preferences
        if (user.notifications_prefs) {
            const pEmail = document.getElementById('notif-pref-email');
            const pWa = document.getElementById('notif-pref-whatsapp');
            const pSms = document.getElementById('notif-pref-sms');

            if (pEmail) pEmail.checked = user.notifications_prefs.email !== false;
            if (pWa) pWa.checked = user.notifications_prefs.whatsapp !== false;
            if (pSms) pSms.checked = user.notifications_prefs.sms === true;
        }
    }

    // Load Shipments
    async function loadCustomerShipments() {
        try {
            const res = await fetch('/api/customer/shipments');
            if (!res.ok) return;

            customerShipments = await res.json();
            renderShipmentsData(customerShipments);
        } catch (err) {
            console.error('Failed to load customer shipments:', err);
        }
    }

    function renderShipmentsData(shipments) {
        let activeCount = 0;
        let deliveredCount = 0;
        let pendingCount = 0;
        let delayedCount = 0;

        shipments.forEach(s => {
            const st = (s.status || '').toLowerCase();
            if (st.includes('delivered')) {
                deliveredCount++;
            } else if (st.includes('customs') || st.includes('pending')) {
                pendingCount++;
                activeCount++;
            } else if (st.includes('hold') || st.includes('delayed')) {
                delayedCount++;
                activeCount++;
            } else {
                activeCount++;
            }
        });

        // Update Stat Cards
        const elActive = document.getElementById('stat-active');
        const elDelivered = document.getElementById('stat-delivered');
        const elPending = document.getElementById('stat-pending');
        const elDelayed = document.getElementById('stat-delayed');
        const tabCount = document.getElementById('tab-shipment-count');

        if (elActive) elActive.textContent = activeCount;
        if (elDelivered) elDelivered.textContent = deliveredCount;
        if (elPending) elPending.textContent = pendingCount;
        if (elDelayed) elDelayed.textContent = delayedCount;
        if (tabCount) tabCount.textContent = shipments.length;

        // Render Overview Active Table
        const overviewBody = document.getElementById('overview-shipments-body');
        if (overviewBody) {
            const activeList = shipments.filter(s => !(s.status || '').toLowerCase().includes('delivered'));
            if (activeList.length === 0) {
                overviewBody.innerHTML = '<tr><td colspan="5" style="padding: 1rem; text-align: center; color: #64748B;">No active consignments found in account.</td></tr>';
            } else {
                let html = '';
                activeList.forEach(s => {
                    html += `
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.75rem 1rem;"><strong style="font-family: monospace; color: #0F172A;">${s.tracking_number}</strong></td>
                            <td style="padding: 0.75rem 1rem;">${s.origin} ➔ ${s.destination}</td>
                            <td style="padding: 0.75rem 1rem;">${getStatusBadge(s.status)}</td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; color: #64748B;">${s.estimated_delivery || 'TBD'}</td>
                            <td style="padding: 0.75rem 1rem;">
                                <button class="btn-view-shipment" data-code="${s.tracking_number}" style="background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; cursor: pointer;">View Details</button>
                            </td>
                        </tr>
                    `;
                });
                overviewBody.innerHTML = html;
            }
        }

        // Render My Shipments Table
        const myBody = document.getElementById('my-shipments-table-body');
        if (myBody) {
            if (shipments.length === 0) {
                myBody.innerHTML = '<tr><td colspan="6" style="padding: 1.5rem; text-align: center; color: #64748B;">No shipments linked to your customer profile.</td></tr>';
            } else {
                renderMyShipmentsTable(shipments);
            }
        }

        // Render History Table
        const histBody = document.getElementById('history-shipments-table-body');
        if (histBody) {
            const deliveredList = shipments.filter(s => (s.status || '').toLowerCase().includes('delivered'));
            if (deliveredList.length === 0) {
                histBody.innerHTML = '<tr><td colspan="5" style="padding: 1.5rem; text-align: center; color: #64748B;">No delivered shipments archived yet.</td></tr>';
            } else {
                let html = '';
                deliveredList.forEach(s => {
                    html += `
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 0.85rem 1rem;"><strong style="font-family: monospace; color: #0F172A;">${s.tracking_number}</strong></td>
                            <td style="padding: 0.85rem 1rem;">${s.origin} ➔ ${s.destination}</td>
                            <td style="padding: 0.85rem 1rem;">${s.actual_delivery || s.estimated_delivery || 'Delivered'}</td>
                            <td style="padding: 0.85rem 1rem; font-size: 0.8rem; color: #059669; font-weight: 700;">Electronic POD Confirmed</td>
                            <td style="padding: 0.85rem 1rem;">
                                <button class="btn-view-shipment" data-code="${s.tracking_number}" style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; font-weight: 700; font-size: 0.75rem; padding: 4px 10px; border-radius: 4px; cursor: pointer;">View POD</button>
                            </td>
                        </tr>
                    `;
                });
                histBody.innerHTML = html;
            }
        }

        bindViewShipmentButtons();
    }

    function renderMyShipmentsTable(list) {
        const myBody = document.getElementById('my-shipments-table-body');
        if (!myBody) return;
        let html = '';
        list.forEach(s => {
            html += `
                <tr style="border-bottom: 1px solid #F1F5F9;">
                    <td style="padding: 0.85rem 1rem;"><strong style="font-family: monospace; font-size: 0.9rem; color: #0F172A;">${s.tracking_number}</strong></td>
                    <td style="padding: 0.85rem 1rem;"><strong>${s.origin}</strong><br><span style="font-size: 0.75rem; color: #64748B;">➔ ${s.destination}</span></td>
                    <td style="padding: 0.85rem 1rem;">${getStatusBadge(s.status)}</td>
                    <td style="padding: 0.85rem 1rem; font-size: 0.8rem; color: #475569;">${s.carrier || 'Qidex Express'}<br><span style="color: #64748B;">${s.service_type || ''}</span></td>
                    <td style="padding: 0.85rem 1rem; font-size: 0.8rem; color: #64748B;">${s.estimated_delivery || 'TBD'}</td>
                    <td style="padding: 0.85rem 1rem;">
                        <button class="btn-view-shipment" data-code="${s.tracking_number}" style="background: #2563EB; color: #FFFFFF; border: none; font-weight: 700; font-size: 0.75rem; padding: 6px 12px; border-radius: 4px; cursor: pointer;">View Shipment</button>
                    </td>
                </tr>
            `;
        });
        myBody.innerHTML = html;
        bindViewShipmentButtons();
    }

    function getStatusBadge(status) {
        const st = (status || '').toLowerCase();
        let bg = '#DBEAFE', color = '#1D4ED8';
        if (st.includes('delivered')) {
            bg = '#D1FAE5'; color = '#047857';
        } else if (st.includes('customs') || st.includes('hold')) {
            bg = '#FEF3C7'; color = '#B45309';
        }
        return `<span style="background: ${bg}; color: ${color}; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; padding: 2px 8px; border-radius: 999px;">${status}</span>`;
    }

    // Search Filter
    const searchInput = document.getElementById('cust-shipment-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const filtered = customerShipments.filter(s =>
                s.tracking_number.toLowerCase().includes(query) ||
                s.origin.toLowerCase().includes(query) ||
                s.destination.toLowerCase().includes(query) ||
                s.status.toLowerCase().includes(query)
            );
            renderMyShipmentsTable(filtered);
        });
    }

    // View Shipment Detail Modal & Access Control
    function bindViewShipmentButtons() {
        document.querySelectorAll('.btn-view-shipment').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                openShipmentModal(code);
            });
        });
    }

    async function openShipmentModal(code) {
        const modal = document.getElementById('shipment-detail-modal');
        const elNo = document.getElementById('modal-shipment-no');
        const elStatus = document.getElementById('modal-shipment-status');
        const elContent = document.getElementById('modal-shipment-content');

        if (!modal) return;

        elNo.textContent = code;
        elContent.innerHTML = '<div style="padding: 2rem; text-align: center; color: #64748B;">Verifying authorization and loading consignment metadata...</div>';
        modal.style.display = 'flex';

        try {
            const res = await fetch(`/api/customer/shipments/${encodeURIComponent(code)}`);
            if (res.status === 403) {
                elContent.innerHTML = `
                    <div style="background: #FEE2E2; border: 1px solid #F87171; color: #B91C1C; padding: 1.5rem; border-radius: 6px; text-align: center;">
                        <h3 style="font-size: 1rem; font-weight: 800; margin-bottom: 0.5rem;">🔒 Access Denied (Unauthorized Consignment)</h3>
                        <p style="font-size: 0.85rem;">This shipment is assigned to another commercial customer account. You are only authorized to view your own waybill records.</p>
                    </div>
                `;
                return;
            }

            if (!res.ok) {
                elContent.innerHTML = '<div style="padding: 1.5rem; text-align: center; color: #EF4444;">Shipment details not found.</div>';
                return;
            }

            const data = await res.json();
            elStatus.textContent = data.status;

            let eventsHtml = '';
            if (data.events && data.events.length > 0) {
                data.events.forEach((evt, idx) => {
                    eventsHtml += `
                        <div style="border-left: 2px solid #2563EB; padding-left: 12px; margin-bottom: 10px; position: relative;">
                            <div style="font-weight: 800; font-size: 0.85rem; color: #0F172A;">${evt.status}</div>
                            <div style="font-size: 0.75rem; color: #64748B;">${evt.date} ${evt.time ? '- ' + evt.time : ''} (${evt.location || 'Terminal Hub'})</div>
                            <p style="font-size: 0.8rem; color: #475569; margin-top: 2px;">${evt.description || ''}</p>
                        </div>
                    `;
                });
            } else {
                eventsHtml = '<div style="font-size: 0.8rem; color: #64748B;">No milestone events recorded yet.</div>';
            }

            elContent.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem; border-radius: 6px; margin-bottom: 1.25rem;">
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Route Overview</div>
                        <div style="font-weight: 800; font-size: 0.9rem; color: #0F172A; margin-top: 2px;">${data.origin} ➔ ${data.destination}</div>
                        <div style="font-size: 0.8rem; color: #2563EB; margin-top: 4px;">📍 Current: <strong>${data.current_location || 'In Transit'}</strong></div>
                    </div>
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748B; text-transform: uppercase;">Carrier & Cargo Specs</div>
                        <div style="font-size: 0.85rem; color: #0F172A; font-weight: 700; margin-top: 2px;">${data.carrier || 'Qidex Express'} (${data.service_type || 'Air Cargo'})</div>
                        <div style="font-size: 0.8rem; color: #64748B; margin-top: 4px;">Package: ${data.package_type || 'Pallet'} • Weight: ${data.weight || 'N/A'}</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; font-size: 0.8rem;">
                    <div style="border: 1px solid #E2E8F0; padding: 0.75rem; border-radius: 6px;">
                        <strong style="color: #475569; display: block; margin-bottom: 2px;">Consignor (Sender):</strong>
                        <div>${data.sender || 'N/A'}</div>
                    </div>
                    <div style="border: 1px solid #E2E8F0; padding: 0.75rem; border-radius: 6px;">
                        <strong style="color: #475569; display: block; margin-bottom: 2px;">Consignee (Receiver):</strong>
                        <div>${data.receiver || 'N/A'}</div>
                    </div>
                </div>

                <h4 style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #0F172A; margin-bottom: 0.75rem;">Milestone Audit Timeline</h4>
                <div>${eventsHtml}</div>
            `;
        } catch(err) {
            elContent.innerHTML = '<div style="padding: 1.5rem; text-align: center; color: #EF4444;">Server error retrieving consignment details.</div>';
        }
    }

    const btnCloseModal = document.getElementById('btn-close-shipment-modal');
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', () => {
            document.getElementById('shipment-detail-modal').style.display = 'none';
        });
    }

    // Dashboard Track Tab Search
    const btnDashTrack = document.getElementById('btn-dash-track');
    const inputDashTrack = document.getElementById('dash-track-input');
    const resultDashTrack = document.getElementById('dash-track-result');

    if (btnDashTrack && inputDashTrack && resultDashTrack) {
        btnDashTrack.addEventListener('click', async function() {
            const code = inputDashTrack.value.trim();
            if (!code) return;

            resultDashTrack.style.display = 'block';
            resultDashTrack.innerHTML = '<div style="text-align: center; color: #64748B;">Querying live radar telemetry...</div>';

            try {
                const res = await fetch(`/api/shipments/track/${encodeURIComponent(code)}`);
                if (res.ok) {
                    const data = await res.json();
                    resultDashTrack.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <h3 style="font-family: monospace; font-size: 1.1rem; color: #0F172A; margin: 0;">${data.tracking_number}</h3>
                            ${getStatusBadge(data.status)}
                        </div>
                        <p style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong>Route:</strong> ${data.origin} ➔ ${data.destination}</p>
                        <p style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong>Location:</strong> ${data.current_location}</p>
                        <p style="font-size: 0.85rem;"><strong>Est. Delivery:</strong> ${data.estimated_delivery}</p>
                    `;
                } else {
                    resultDashTrack.innerHTML = `<div style="color: #EF4444; font-size: 0.85rem;">Tracking code '${code}' not found in active database.</div>`;
                }
            } catch(e) {
                resultDashTrack.innerHTML = `<div style="color: #EF4444; font-size: 0.85rem;">Lookup error: ${e.message}</div>`;
            }
        });
    }

    // Profile Update Form
    const profileForm = document.getElementById('profile-update-form');
    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const payload = {
                first_name: document.getElementById('prof-firstname').value.trim(),
                last_name: document.getElementById('prof-lastname').value.trim(),
                phone: document.getElementById('prof-phone').value.trim(),
                company: document.getElementById('prof-company').value.trim(),
                current_password: document.getElementById('prof-curr-pass').value,
                new_password: document.getElementById('prof-new-pass').value,
                confirm_new_password: document.getElementById('prof-confirm-pass').value
            };

            try {
                const res = await fetch('/api/customer/profile', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showAlert('Profile settings saved successfully!', true);
                    document.getElementById('prof-curr-pass').value = '';
                    document.getElementById('prof-new-pass').value = '';
                    document.getElementById('prof-confirm-pass').value = '';
                } else {
                    showAlert(data.error || 'Failed to update profile.');
                }
            } catch(e) {
                showAlert('Server error saving profile: ' + e.message);
            }
        });
    }

    // Notifications Preferences Save
    const btnSaveNotif = document.getElementById('btn-save-notif-prefs');
    if (btnSaveNotif) {
        btnSaveNotif.addEventListener('click', async function() {
            const prefs = {
                email: document.getElementById('notif-pref-email').checked,
                whatsapp: document.getElementById('notif-pref-whatsapp').checked,
                sms: document.getElementById('notif-pref-sms').checked
            };

            try {
                const res = await fetch('/api/customer/notifications', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(prefs)
                });
                if (res.ok) {
                    showAlert('Notification channel preferences updated!', true);
                }
            } catch(e) {
                showAlert('Error updating preferences.');
            }
        });
    }

    function renderNotificationsFeed(feed) {
        const feedList = document.getElementById('notifications-feed-list');
        if (!feedList) return;

        if (feed.length === 0) {
            feedList.innerHTML = '<div style="color: #64748B; font-size: 0.85rem;">No notifications recorded.</div>';
            return;
        }

        let html = '';
        feed.forEach(item => {
            html += `
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 0.85rem 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                        <strong style="font-size: 0.85rem; color: #0F172A;">${item.title}</strong>
                        <span style="font-size: 0.75rem; color: #94A3B8;">${item.timestamp || 'Just now'}</span>
                    </div>
                    <p style="font-size: 0.8rem; color: #475569; margin: 0;">${item.message}</p>
                </div>
            `;
        });
        feedList.innerHTML = html;
    }

    // Support Ticket Form
    const supportForm = document.getElementById('support-ticket-form');
    if (supportForm) {
        supportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showAlert('Support inquiry submitted. An assigned logistics officer will contact you shortly.', true);
            supportForm.reset();
        });
    }

    // Logout Button
    if (btnLogout) {
        btnLogout.addEventListener('click', async function() {
            try {
                await fetch('/api/auth/logout', { method: 'POST' });
                window.location.href = '/login/';
            } catch(e) {
                window.location.href = '/login/';
            }
        });
    }

    // Initialize Dashboard
    initDashboard();
});
