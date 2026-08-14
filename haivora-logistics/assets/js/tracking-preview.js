/**
 * Phase 4 Public Tracking Engine JavaScript
 * Connects to live shipment API and renders admin-updated telemetry & events
 */
document.addEventListener('DOMContentLoaded', function() {
    const btnPageTrack = document.getElementById('btn-page-track');
    const inputPageTrack = document.getElementById('page-tracking-input');
    const demoPills = document.querySelectorAll('.demo-pill');

    const btnSubmitTrack = document.getElementById('btn-submit-track');
    const inputField = document.getElementById('tracking-input-field');
    const resultsBox = document.getElementById('tracking-results-box');

    async function fetchShipmentData(trackingCode) {
        const cleanCode = trackingCode.trim().toUpperCase();
        try {
            const response = await fetch(`/api/shipments/track/${encodeURIComponent(cleanCode)}`);
            if (response.ok) {
                const data = await response.json();
                return data;
            }
        } catch (err) {
            console.warn('API lookup failed, falling back to local dataset', err);
        }
        return null;
    }

    function updateTrackingUI(data) {
        if (!data) return;

        // Header elements
        const elId = document.getElementById('result-shipment-id');
        const elBadge = document.getElementById('result-status-badge');
        const elOrigin = document.getElementById('meta-origin');
        const elDest = document.getElementById('meta-destination');
        const elLoc = document.getElementById('meta-location');
        const elDelivery = document.getElementById('meta-delivery');
        const elShipDate = document.getElementById('meta-shipdate');
        const elLastUpdate = document.getElementById('meta-lastupdate');
        const elCarrier = document.getElementById('meta-carrier');
        const elPackageType = document.getElementById('meta-packagetype');

        if (elId) elId.textContent = data.tracking_number;
        if (elBadge) {
            elBadge.textContent = (data.status || 'IN TRANSIT').toUpperCase();
            
            // Badge color styling
            const st = (data.status || '').toLowerCase();
            if (st.includes('delivered')) {
                elBadge.style.backgroundColor = 'rgba(16, 185, 129, 0.15)';
                elBadge.style.color = '#059669';
            } else if (st.includes('customs') || st.includes('hold')) {
                elBadge.style.backgroundColor = 'rgba(245, 158, 11, 0.15)';
                elBadge.style.color = '#D97706';
            } else {
                elBadge.style.backgroundColor = 'rgba(37, 99, 235, 0.15)';
                elBadge.style.color = '#2563EB';
            }
        }

        if (elOrigin) elOrigin.textContent = data.origin || 'N/A';
        if (elDest) elDest.textContent = data.destination || 'N/A';
        if (elLoc) elLoc.textContent = data.current_location || 'En Route';
        if (elDelivery) elDelivery.textContent = data.estimated_delivery || 'Pending';
        if (elShipDate) elShipDate.textContent = data.shipment_date || 'N/A';
        if (elLastUpdate) elLastUpdate.textContent = data.last_updated ? new Date(data.last_updated).toLocaleTimeString() : 'Just now';
        if (elCarrier) elCarrier.textContent = data.carrier || 'Qidex Express Logistics';
        if (elPackageType) elPackageType.textContent = (data.service_type || '') + (data.package_type ? ' (' + data.package_type + ')' : '');

        // Render Tracking Events Timeline
        const timelineContainer = document.querySelector('.tracking-timeline');
        if (timelineContainer && data.events && Array.isArray(data.events)) {
            if (data.events.length === 0) {
                timelineContainer.innerHTML = '<div style="padding: 1rem; color: #64748b;">No milestone events recorded yet.</div>';
            } else {
                let html = '';
                data.events.forEach((evt, idx) => {
                    const isLast = (idx === data.events.length - 1);
                    const isDelivered = (data.status || '').toLowerCase() === 'delivered';
                    const isCompleted = !isLast || isDelivered;
                    const isCurrent = isLast && !isDelivered;

                    let stepClass = 'timeline-step';
                    if (isCompleted) stepClass += ' completed';
                    if (isCurrent) stepClass += ' current';

                    html += `
                        <div class="${stepClass}">
                            <div class="step-title">${evt.status || ('Milestone #' + (idx + 1))}</div>
                            <div class="step-time">${evt.date || ''} ${evt.time ? '- ' + evt.time : ''} ${evt.location ? ' (' + evt.location + ')' : ''}</div>
                            <p style="font-size: 0.85rem; color: #64748B; margin-top: 0.25rem;">${evt.description || ''}</p>
                        </div>
                    `;
                });
                timelineContainer.innerHTML = html;
            }
        }

        // Also update home page results box if present
        if (resultsBox) {
            const resId = document.getElementById('result-shipment-id');
            const resBadge = document.getElementById('result-status-badge');
            const resRoute = document.getElementById('result-route');
            const resCargo = document.getElementById('result-cargo-type');

            if (resId) resId.textContent = data.tracking_number;
            if (resBadge) resBadge.textContent = data.status;
            if (resRoute) resRoute.innerHTML = `${data.origin} → ${data.destination}`;
            if (resCargo) resCargo.textContent = `${data.service_type || ''} ${data.weight ? ' • ' + data.weight : ''}`;

            resultsBox.classList.add('is-active');
            resultsBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    async function handleTrackRequest(code) {
        if (!code) return;
        const data = await fetchShipmentData(code);
        if (data) {
            updateTrackingUI(data);
        } else {
            alert('Tracking number ' + code + ' not found in active database. Try sample references like QX-8829-US, QX-9912-DE, or QX-3301-CN.');
        }
    }

    // Bind page events
    if (btnPageTrack && inputPageTrack) {
        btnPageTrack.addEventListener('click', function() {
            handleTrackRequest(inputPageTrack.value);
        });
        inputPageTrack.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleTrackRequest(inputPageTrack.value);
            }
        });
    }

    if (btnSubmitTrack && inputField) {
        btnSubmitTrack.addEventListener('click', function() {
            handleTrackRequest(inputField.value);
        });
    }

    demoPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            if (inputPageTrack) inputPageTrack.value = code;
            if (inputField) inputField.value = code;
            handleTrackRequest(code);
        });
    });

    // Initial load for default code QX-8829-US on track-shipment page
    if (document.getElementById('tracking-detail-card')) {
        handleTrackRequest('QX-8829-US');
    }
});
