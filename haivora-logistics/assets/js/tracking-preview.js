/**
 * Phase 1 Interactive Tracking Preview JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    const btnSubmitTrack = document.getElementById('btn-submit-track');
    const inputField = document.getElementById('tracking-input-field');
    const resultsBox = document.getElementById('tracking-results-box');
    const demoPills = document.querySelectorAll('.demo-pill');

    const sampleDatabase = {
        'QX-8829-US': {
            id: 'QX-8829-US',
            status: 'IN TRANSIT',
            statusClass: 'in-transit',
            route: 'JFK, New York → FRA, Frankfurt',
            cargo: 'Air Freight (Temperature Controlled)',
            steps: [
                { title: '1. Shipment Created', time: 'Aug 12, 2026 - 08:30 AM', desc: 'Dispatch confirmed at NY Logistics Hub.', completed: true },
                { title: '2. Export Customs Cleared', time: 'Aug 13, 2026 - 14:15 PM', desc: 'Manifest approved by US Customs.', completed: true },
                { title: '3. In Transit (Transatlantic)', time: 'Aug 14, 2026 - Active', desc: 'Onboard Flight QX-702 over Atlantic Ocean.', completed: false, current: true },
                { title: '4. Out for Final Delivery', time: 'Estimated: Today - 16:30 PM', desc: 'Pending arrival at Frankfurt Terminal.', completed: false }
            ]
        },
        'QX-9912-DE': {
            id: 'QX-9912-DE',
            status: 'DELIVERED',
            statusClass: 'delivered',
            route: 'HAM, Hamburg → SIN, Singapore',
            cargo: 'FCL Ocean Cargo (2x 40ft High Cube)',
            steps: [
                { title: '1. Shipment Created', time: 'Aug 01, 2026 - 10:00 AM', desc: 'Loaded onto vessel Qidex Voyager.', completed: true },
                { title: '2. Port Transit Cleared', time: 'Aug 05, 2026 - 11:30 AM', desc: 'Suez Canal maritime passage complete.', completed: true },
                { title: '3. Arrived at Destination Hub', time: 'Aug 12, 2026 - 09:15 AM', desc: 'Unloaded at Port of Singapore.', completed: true },
                { title: '4. Delivered & Signed', time: 'Aug 13, 2026 - 15:45 PM', desc: 'Signed by Consignee (e-POD confirmed).', completed: true }
            ]
        },
        'QX-3301-CN': {
            id: 'QX-3301-CN',
            status: 'CUSTOMS CLEARANCE',
            statusClass: 'in-transit',
            route: 'PVG, Shanghai → LHR, London',
            cargo: 'Express Courier Parcel',
            steps: [
                { title: '1. Shipment Picked Up', time: 'Aug 12, 2026 - 18:00 PM', desc: 'Picked up from Shanghai Warehouse.', completed: true },
                { title: '2. Import Customs Review', time: 'Aug 14, 2026 - Active', desc: 'Documentation undergoing HMRC review at London Heathrow.', completed: false, current: true },
                { title: '3. Out for Last-Mile Courier', time: 'Pending Customs Release', desc: 'Assigned to London Express Van #4.', completed: false },
                { title: '4. Doorstep Delivery', time: 'Estimated: Tomorrow', desc: 'Signature required upon arrival.', completed: false }
            ]
        }
    };

    function renderTrackingResult(code) {
        if (!resultsBox) return;

        const cleanCode = code.trim().toUpperCase();
        const data = sampleDatabase[cleanCode] || {
            id: cleanCode,
            status: 'IN TRANSIT',
            statusClass: 'in-transit',
            route: 'Global Origin → Global Destination',
            cargo: 'Standard Commercial Cargo',
            steps: [
                { title: '1. Waybill Generated', time: 'Aug 13, 2026', desc: 'Registered in Qidex Global Logistics Network.', completed: true },
                { title: '2. Port Clearance', time: 'Aug 14, 2026', desc: 'Export clearance in progress.', completed: false, current: true },
                { title: '3. Transit Flight/Vessel', time: 'En Route', desc: 'International transport assigned.', completed: false },
                { title: '4. Final Destination', time: 'Pending Arrival', desc: 'Scheduled for local delivery.', completed: false }
            ]
        };

        const resId = document.getElementById('result-shipment-id');
        const resBadge = document.getElementById('result-status-badge');
        const resRoute = document.getElementById('result-route');
        const resCargo = document.getElementById('result-cargo-type');

        if (resId) resId.textContent = data.id;
        if (resBadge) {
            resBadge.textContent = data.status;
            resBadge.className = 'status-badge ' + data.statusClass;
        }
        if (resRoute) resRoute.innerHTML = data.route;
        if (resCargo) resCargo.textContent = data.cargo;

        const timelineContainer = resultsBox.querySelector('.tracking-timeline');
        if (timelineContainer) {
            let html = '';
            data.steps.forEach(step => {
                let stepClass = 'timeline-step';
                if (step.completed) stepClass += ' completed';
                if (step.current) stepClass += ' current';

                html += `
                    <div class="${stepClass}">
                        <div class="step-title">${step.title}</div>
                        <div class="step-time">${step.time}</div>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">${step.desc}</p>
                    </div>
                `;
            });
            timelineContainer.innerHTML = html;
        }

        resultsBox.classList.add('is-active');
        resultsBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    if (btnSubmitTrack && inputField) {
        btnSubmitTrack.addEventListener('click', function() {
            const val = inputField.value || 'QX-8829-US';
            renderTrackingResult(val);
        });
    }

    demoPills.forEach(pill => {
        pill.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            if (inputField) inputField.value = code;
            renderTrackingResult(code);
        });
    });
});
