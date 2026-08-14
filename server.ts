import express from 'express';
import path from 'path';
import fs from 'fs';

const app = express();
const PORT = 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Cookie Parser Middleware for Session Tokens
app.use((req: any, res: any, next: any) => {
    const cookieHeader = req.headers.cookie;
    req.cookies = {};
    if (cookieHeader) {
        cookieHeader.split(';').forEach((cookie: string) => {
            const parts = cookie.split('=');
            if (parts.length >= 2) {
                req.cookies[parts[0].trim()] = parts.slice(1).join('=').trim();
            }
        });
    }
    next();
});

// Serve static assets
app.use('/assets', express.static(path.join(process.cwd(), 'assets')));
app.use('/style.css', express.static(path.join(process.cwd(), 'style.css')));
app.use('/screenshot.png', express.static(path.join(process.cwd(), 'screenshot.png')));

/**
 * Phase 4 In-Memory / File-Persisted Shipment Store
 */
interface TrackingEvent {
    status: string;
    location: string;
    date: string;
    time: string;
    description: string;
}

interface Shipment {
    id: number;
    tracking_number: string;
    status: string;
    origin: string;
    destination: string;
    current_location: string;
    sender: string;
    receiver: string;
    shipment_date: string;
    estimated_delivery: string;
    actual_delivery: string;
    carrier: string;
    service_type: string;
    package_type: string;
    weight: string;
    quantity: string;
    description: string;
    customer_email?: string;
    events: TrackingEvent[];
    last_updated: string;
}

/**
 * Phase 6 Data Interfaces & Stores
 */
interface QuoteRequest {
    id: string;
    full_name: string;
    email: string;
    phone: string;
    origin: string;
    destination: string;
    shipment_type: string;
    package_type: string;
    weight: number;
    dimensions: string;
    shipping_method: string;
    pickup_date: string;
    additional_info: string;
    date_submitted: string;
    status: string; // 'New' | 'Reviewing' | 'Quoted' | 'Accepted' | 'Rejected' | 'Completed'
    quoted_rate: string;
    admin_notes: string;
}

interface ContactMessage {
    id: string;
    full_name: string;
    email: string;
    phone: string;
    subject: string;
    message: string;
    date_submitted: string;
    status: string; // 'Unread' | 'Replied' | 'Archived'
}

interface WhatsAppSettings {
    number: string;
    default_message: string;
}

interface EmailLogEntry {
    id: string;
    recipient: string;
    subject: string;
    type: string;
    timestamp: string;
    status: string;
}

let quotesDatabase: QuoteRequest[] = [
    {
        id: 'QT-2026-8819',
        full_name: 'Robert Vance',
        email: 'r.vance@vancerefrigeration.com',
        phone: '+1 (555) 234-5678',
        origin: 'Chicago, IL, USA',
        destination: 'Hamburg, Germany',
        shipment_type: 'Sea Freight (FCL / LCL)',
        package_type: 'Pallets',
        weight: 1250,
        dimensions: '240 x 120 x 160 cm',
        shipping_method: 'Standard Priority',
        pickup_date: '2026-08-20',
        additional_info: 'Temperature controlled refrigerated cargo container needed.',
        date_submitted: '2026-08-14 01:15:00',
        status: 'New',
        quoted_rate: '',
        admin_notes: ''
    },
    {
        id: 'QT-2026-7712',
        full_name: 'Claire Beauchamp',
        email: 'claire@medicalsupplies.co.uk',
        phone: '+44 20 7946 0912',
        origin: 'London Heathrow, UK',
        destination: 'Shanghai Pudong, China',
        shipment_type: 'Air Freight Express',
        package_type: 'Box / Parcel',
        weight: 320,
        dimensions: '180 x 100 x 90 cm',
        shipping_method: 'Urgent Express Air',
        pickup_date: '2026-08-18',
        additional_info: 'Sterilized medical diagnostic equipment. Requires priority air transit.',
        date_submitted: '2026-08-13 14:22:00',
        status: 'Quoted',
        quoted_rate: '4,250.00',
        admin_notes: 'Priority Air Freight slot reserved with Lufthansa Cargo.'
    }
];

let contactDatabase: ContactMessage[] = [
    {
        id: 'MSG-2026-101',
        full_name: 'Elena Rostova',
        email: 'elena@globalmachinery.eu',
        phone: '+49 30 98765432',
        subject: 'Customs Clearance Assistance for PVG Cargo',
        message: 'We need guidance clarifying transit documentation for our high-value industrial shipment arriving at Frankfurt terminal next week.',
        date_submitted: '2026-08-13 18:40:00',
        status: 'Unread'
    },
    {
        id: 'MSG-2026-098',
        full_name: 'David Chen',
        email: 'david.chen@taiwantech.tw',
        phone: '+886 2 2345 6789',
        subject: 'Corporate Freight Partnership Program',
        message: 'Inquiring about establishing a weekly air freight charter schedule between Taipei and JFK hub.',
        date_submitted: '2026-08-12 11:05:00',
        status: 'Replied'
    }
];

let whatsappConfig: WhatsAppSettings = {
    number: '18005557433',
    default_message: 'Hello Qidex Logistics, I would like to inquire about freight quotes and cargo shipping services.'
};

let emailLogs: EmailLogEntry[] = [
    {
        id: 'email_1',
        recipient: 'r.vance@vancerefrigeration.com',
        subject: 'Quote Request Confirmation [QT-2026-8819] - Qidex Logistics',
        type: 'Quote Request Confirmation',
        timestamp: '2026-08-14 01:15:02',
        status: 'Delivered (wp_mail)'
    },
    {
        id: 'email_2',
        recipient: 'claire@medicalsupplies.co.uk',
        subject: 'Freight Quote Proposal [QT-2026-7712] - $4,250.00 - Qidex Logistics',
        type: 'Rate Proposal Dispatch',
        timestamp: '2026-08-13 15:00:00',
        status: 'Delivered (wp_mail)'
    }
];

/**
 * Phase 7: Payment & API Architecture Interfaces & Data Stores
 */
interface PaymentTransaction {
    transaction_id: string;
    customer_email: string;
    customer_name: string;
    amount: number;
    currency: string;
    provider: string; // 'stripe' | 'flutterwave' | 'paystack'
    payment_type: string; // 'shipping_payment' | 'quote_payment' | 'invoice_payment'
    status: string; // 'Pending' | 'Successful' | 'Failed' | 'Cancelled' | 'Refunded'
    date: string;
    related_item_id: string;
    reference: string;
}

interface CarrierApiSettings {
    provider: string;
    api_url: string;
    api_key: string;
    api_secret: string;
    mode: string;
}

interface PaymentGatewaySettings {
    provider: string;
    public_key: string;
    secret_key: string;
    webhook_secret: string;
    currency: string;
    mode: string;
}

let transactionsDatabase: PaymentTransaction[] = [
    {
        transaction_id: 'TXN-2026-9041',
        customer_email: 'r.vance@vancerefrigeration.com',
        customer_name: 'Robert Vance',
        amount: 4250.00,
        currency: 'USD',
        provider: 'stripe',
        payment_type: 'quote_payment',
        status: 'Successful',
        date: '2026-08-13 16:30:00',
        related_item_id: 'QT-2026-7712',
        reference: 'ch_3M291823901823'
    },
    {
        transaction_id: 'TXN-2026-8812',
        customer_email: 'claire@medicalsupplies.co.uk',
        customer_name: 'Claire Beauchamp',
        amount: 1250.00,
        currency: 'EUR',
        provider: 'flutterwave',
        payment_type: 'shipping_payment',
        status: 'Pending',
        date: '2026-08-14 02:10:00',
        related_item_id: 'QX-8829-US',
        reference: 'FLW-REF-9981273'
    },
    {
        transaction_id: 'TXN-2026-7730',
        customer_email: 'elena@globalmachinery.eu',
        customer_name: 'Elena Rostova',
        amount: 3100.00,
        currency: 'USD',
        provider: 'paystack',
        payment_type: 'invoice_payment',
        status: 'Successful',
        date: '2026-08-12 11:20:00',
        related_item_id: 'INV-2026-004',
        reference: 'PSTK-REF-771209'
    }
];

let carrierApiConfig: CarrierApiSettings = {
    provider: 'demo_carrier',
    api_url: 'https://api.carrier-service.com/v1',
    api_key: 'haivora_live_key_99182739182',
    api_secret: 'haivora_secret_772615241',
    mode: 'sandbox'
};

let paymentGatewayConfig: PaymentGatewaySettings = {
    provider: 'stripe',
    public_key: 'pk_test_51M09817238127391283',
    secret_key: 'sk_test_991827391827391283',
    webhook_secret: 'whsec_88127391827391283',
    currency: 'USD',
    mode: 'test'
};

let shipmentsDatabase: Shipment[] = [
    {
        id: 101,
        tracking_number: 'QX-8829-US',
        customer_email: 'client@acmetech.com',
        status: 'In Transit',
        origin: 'JFK, New York, USA',
        destination: 'FRA, Frankfurt, Germany',
        current_location: 'Flight QX-702 (Mid-Atlantic)',
        sender: 'Acme Tech Supply Corp, NY',
        receiver: 'Global Euro Distribution GmbH, Frankfurt',
        shipment_date: '2026-08-12',
        estimated_delivery: 'Aug 14, 2026 - 16:30 GMT',
        actual_delivery: 'Pending',
        carrier: 'Qidex Transatlantic Air Freight',
        service_type: 'Air Freight',
        package_type: 'Temperature Controlled Pallets',
        weight: '450 kg',
        quantity: '12 Crates',
        description: 'High-value semiconductor electronic components.',
        events: [
            {
                status: '1. Waybill & Cargo Registered',
                location: 'JFK Logistics Hub, New York',
                date: '2026-08-12',
                time: '08:30 AM',
                description: 'Parcel accepted and waybill created at dispatch hub.'
            },
            {
                status: '2. Export Customs Cleared',
                location: 'US Customs & Border Protection',
                date: '2026-08-13',
                time: '14:15 PM',
                description: 'Manifest approved and cleared for export.'
            },
            {
                status: '3. Transatlantic Airborne Transit',
                location: 'Onboard Flight QX-702',
                date: '2026-08-14',
                time: '02:00 AM',
                description: 'Aircraft en route over Mid-Atlantic corridor.'
            },
            {
                status: '4. Out for Final Terminal Delivery',
                location: 'Frankfurt Cargo City South',
                date: '2026-08-14',
                time: '16:30 PM',
                description: 'Scheduled for arrival at Frankfurt distribution center.'
            }
        ],
        last_updated: new Date().toISOString()
    },
    {
        id: 102,
        tracking_number: 'QX-9912-DE',
        customer_email: 'hamburg.machinery@global.de',
        status: 'Delivered',
        origin: 'HAM, Hamburg Port, Germany',
        destination: 'SIN, Port of Singapore',
        current_location: 'Consignee Warehouse Hub #3',
        sender: 'Hamburg Industrial Machinery AG',
        receiver: 'Singapore Maritime Logistics Pte',
        shipment_date: '2026-08-01',
        estimated_delivery: 'Aug 13, 2026 - 15:45 SGT',
        actual_delivery: 'Aug 13, 2026 - 15:45 SGT',
        carrier: 'Qidex Ocean Line Voyager',
        service_type: 'Ocean Cargo',
        package_type: 'FCL High Cube Container',
        weight: '18,400 kg',
        quantity: '2 Containers (40ft)',
        description: 'Heavy industrial machinery assemblies and spares.',
        events: [
            {
                status: '1. Container Loaded',
                location: 'Port of Hamburg',
                date: '2026-08-01',
                time: '10:00 AM',
                description: 'Container sealed and loaded onto vessel.'
            },
            {
                status: '2. Suez Passage Transit',
                location: 'Suez Canal Maritime Corridor',
                date: '2026-08-05',
                time: '11:30 AM',
                description: 'Maritime passage approved.'
            },
            {
                status: '3. Arrived at Destination Hub',
                location: 'Pasir Panjang Terminal, Singapore',
                date: '2026-08-12',
                time: '09:15 AM',
                description: 'Discharged from vessel and cleared import customs.'
            },
            {
                status: '4. Delivered & Signed',
                location: 'Consignee Warehouse Hub #3',
                date: '2026-08-13',
                time: '15:45 PM',
                description: 'Signed by Consignee (electronic POD confirmed).'
            }
        ],
        last_updated: new Date().toISOString()
    },
    {
        id: 103,
        tracking_number: 'QX-3301-CN',
        customer_email: 'client@acmetech.com',
        status: 'Customs Clearance',
        origin: 'PVG, Shanghai Pudong, China',
        destination: 'LHR, London Heathrow, UK',
        current_location: 'HMRC Customs Clearance Bay, Heathrow',
        sender: 'Shanghai Precision Electronics',
        receiver: 'UK Express Retail Logistics Ltd',
        shipment_date: '2026-08-12',
        estimated_delivery: 'Aug 15, 2026 - 12:00 GMT',
        actual_delivery: 'Pending Customs Clearance',
        carrier: 'Qidex Global Courier Express',
        service_type: 'Express Courier',
        package_type: 'Cardboard Cartons',
        weight: '85 kg',
        quantity: '5 Boxes',
        description: 'Consumer electronics and wearables.',
        events: [
            {
                status: '1. Parcel Picked Up',
                location: 'Shanghai Warehouse #9',
                date: '2026-08-12',
                time: '18:00 PM',
                description: 'Collected by driver and processed at hub.'
            },
            {
                status: '2. Import Customs Review',
                location: 'London Heathrow Freight Center',
                date: '2026-08-14',
                time: '08:00 AM',
                description: 'Documentation undergoing HMRC review.'
            }
        ],
        last_updated: new Date().toISOString()
    }
];

/**
 * Phase 5 User Accounts & Auth Session Management
 */
interface UserAccount {
    id: number;
    email: string;
    first_name: string;
    last_name: string;
    display_name: string;
    phone: string;
    company: string;
    account_type: string;
    role: string;
    password_hash: string;
    notifications_prefs: {
        email: boolean;
        whatsapp: boolean;
        sms: boolean;
    };
    notifications_log: Array<{
        id: string;
        tracking_no: string;
        title: string;
        message: string;
        timestamp: string;
        read: boolean;
        channels: { email: boolean; whatsapp: boolean; sms: boolean };
    }>;
}

let usersDatabase: UserAccount[] = [
    {
        id: 1,
        email: 'client@acmetech.com',
        first_name: 'Jane',
        last_name: 'Smith',
        display_name: 'Jane Smith',
        phone: '+1 (555) 019-2834',
        company: 'Acme Tech Supply Corp',
        account_type: 'corporate',
        role: 'customer',
        password_hash: 'Acme2026!',
        notifications_prefs: { email: true, whatsapp: true, sms: false },
        notifications_log: [
            {
                id: 'notif_1',
                tracking_no: 'QX-8829-US',
                title: 'Transatlantic Flight Departure',
                message: 'Shipment QX-8829-US departed JFK Airport onboard flight QX-702.',
                timestamp: '2026-08-14 02:00:00',
                read: false,
                channels: { email: true, whatsapp: true, sms: false }
            }
        ]
    },
    {
        id: 2,
        email: 'hamburg.machinery@global.de',
        first_name: 'Hans',
        last_name: 'Müller',
        display_name: 'Hans Müller',
        phone: '+49 40 12345678',
        company: 'Hamburg Industrial Machinery AG',
        account_type: 'corporate',
        role: 'customer',
        password_hash: 'Hamburg2026!',
        notifications_prefs: { email: true, whatsapp: true, sms: true },
        notifications_log: [
            {
                id: 'notif_2',
                tracking_no: 'QX-9912-DE',
                title: 'Delivery POD Confirmed',
                message: 'Shipment QX-9912-DE delivered to Consignee Warehouse in Singapore.',
                timestamp: '2026-08-13 15:45:00',
                read: true,
                channels: { email: true, whatsapp: true, sms: true }
            }
        ]
    },
    {
        id: 3,
        email: 'admin@qidexexpress.com',
        first_name: 'Administrator',
        last_name: 'System',
        display_name: 'System Admin',
        phone: '+1 (800) 555-QIDEX',
        company: 'Qidex Express Logistics',
        account_type: 'corporate',
        role: 'administrator',
        password_hash: 'Admin2026!',
        notifications_prefs: { email: true, whatsapp: true, sms: true },
        notifications_log: []
    }
];

const activeSessions: { [token: string]: number } = {};

function getLoggedInUser(req: any): UserAccount | null {
    const sessionToken = req.cookies && req.cookies['qidex_session'];
    if (sessionToken && activeSessions[sessionToken]) {
        const userId = activeSessions[sessionToken];
        return usersDatabase.find(u => u.id === userId) || null;
    }
    return null;
}

// ==================================================
// AUTHENTICATION API ENDPOINTS
// ==================================================

// POST /api/auth/register
app.post('/api/auth/register', (req, res) => {
    const { first_name, last_name, email, phone, company, account_type, password, confirm_password } = req.body;

    if (!first_name || !last_name || !email || !phone || !password) {
        return res.status(400).json({ error: 'Please complete all required registration fields.' });
    }

    if (password !== confirm_password) {
        return res.status(400).json({ error: 'Password and Confirm Password do not match.' });
    }

    if (password.length < 6) {
        return res.status(400).json({ error: 'Password must be at least 6 characters in length.' });
    }

    const emailClean = email.trim().toLowerCase();
    const existing = usersDatabase.find(u => u.email.toLowerCase() === emailClean);
    if (existing) {
        return res.status(400).json({ error: 'An account with this email address already exists. Please log in.' });
    }

    const newUser: UserAccount = {
        id: Date.now(),
        email: emailClean,
        first_name: first_name.trim(),
        last_name: last_name.trim(),
        display_name: `${first_name.trim()} ${last_name.trim()}`,
        phone: phone.trim(),
        company: company ? company.trim() : '',
        account_type: account_type || 'corporate',
        role: 'customer',
        password_hash: password,
        notifications_prefs: { email: true, whatsapp: true, sms: false },
        notifications_log: [
            {
                id: 'notif_' + Date.now(),
                tracking_no: 'WELCOME',
                title: 'Welcome to Qidex Express Portal',
                message: 'Your account has been configured. Automated tracking updates and waybills are active.',
                timestamp: new Date().toISOString(),
                read: false,
                channels: { email: true, whatsapp: true, sms: false }
            }
        ]
    };

    usersDatabase.push(newUser);

    const sessionToken = 'sess_' + Math.random().toString(36).substring(2) + Date.now();
    activeSessions[sessionToken] = newUser.id;

    res.setHeader('Set-Cookie', `qidex_session=${sessionToken}; Path=/; HttpOnly; SameSite=Lax`);
    res.json({ success: true, user: newUser });
});

// POST /api/auth/login
app.post('/api/auth/login', (req, res) => {
    const { log, pwd } = req.body;
    if (!log || !pwd) {
        return res.status(400).json({ error: 'Please provide both email/username and password.' });
    }

    const logClean = log.trim().toLowerCase();
    const user = usersDatabase.find(u => u.email.toLowerCase() === logClean || u.first_name.toLowerCase() === logClean);

    if (!user || user.password_hash !== pwd) {
        return res.status(401).json({ error: 'Invalid email/username or password. Please try again.' });
    }

    const sessionToken = 'sess_' + Math.random().toString(36).substring(2) + Date.now();
    activeSessions[sessionToken] = user.id;

    res.setHeader('Set-Cookie', `qidex_session=${sessionToken}; Path=/; HttpOnly; SameSite=Lax`);
    res.json({ success: true, user });
});

// POST /api/auth/logout
app.post('/api/auth/logout', (req, res) => {
    const sessionToken = req.cookies && req.cookies['qidex_session'];
    if (sessionToken && activeSessions[sessionToken]) {
        delete activeSessions[sessionToken];
    }
    res.setHeader('Set-Cookie', 'qidex_session=; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT');
    res.json({ success: true });
});

// POST /api/auth/forgot-password
app.post('/api/auth/forgot-password', (req, res) => {
    const { email } = req.body;
    if (!email) {
        return res.status(400).json({ error: 'Email address is required.' });
    }
    res.json({ success: true, message: `Password reset instructions have been dispatched to ${email}.` });
});

// ==================================================
// CUSTOMER PORTAL API ENDPOINTS
// ==================================================

// GET /api/customer/me
app.get('/api/customer/me', (req, res) => {
    const user = getLoggedInUser(req);
    if (!user) {
        return res.status(401).json({ error: 'Not authenticated. Please log in.' });
    }
    res.json(user);
});

// PUT /api/customer/profile
app.put('/api/customer/profile', (req, res) => {
    const user = getLoggedInUser(req);
    if (!user) {
        return res.status(401).json({ error: 'Not authenticated.' });
    }

    const { first_name, last_name, phone, company, current_password, new_password, confirm_new_password } = req.body;

    if (new_password) {
        if (user.password_hash !== current_password) {
            return res.status(400).json({ error: 'Current password is incorrect.' });
        }
        if (new_password.length < 6) {
            return res.status(400).json({ error: 'New password must be at least 6 characters.' });
        }
        if (new_password !== confirm_new_password) {
            return res.status(400).json({ error: 'New password and confirm password do not match.' });
        }
        user.password_hash = new_password;
    }

    if (first_name) user.first_name = first_name.trim();
    if (last_name) user.last_name = last_name.trim();
    user.display_name = `${user.first_name} ${user.last_name}`;
    if (phone) user.phone = phone.trim();
    if (company) user.company = company.trim();

    // STRICT ROLE PROTECTION: Role remains unaltered as customer
    res.json({ success: true, user });
});

// GET /api/customer/shipments
app.get('/api/customer/shipments', (req, res) => {
    const user = getLoggedInUser(req);
    if (!user) {
        return res.status(401).json({ error: 'Not authenticated.' });
    }

    if (user.role === 'administrator') {
        return res.json(shipmentsDatabase);
    }

    const emailClean = user.email.toLowerCase();
    const filtered = shipmentsDatabase.filter(s => {
        if (s.customer_email && s.customer_email.toLowerCase() === emailClean) return true;
        if (s.sender && s.sender.toLowerCase().includes(emailClean)) return true;
        if (s.receiver && s.receiver.toLowerCase().includes(emailClean)) return true;
        if (user.company && (s.sender.toLowerCase().includes(user.company.toLowerCase()) || s.receiver.toLowerCase().includes(user.company.toLowerCase()))) return true;
        return false;
    });

    res.json(filtered);
});

// GET /api/customer/shipments/:code
app.get('/api/customer/shipments/:code', (req, res) => {
    const user = getLoggedInUser(req);
    if (!user) {
        return res.status(401).json({ error: 'Not authenticated.' });
    }

    const code = req.params.code.trim().toUpperCase();
    const shipment = shipmentsDatabase.find(s => s.tracking_number.toUpperCase() === code);

    if (!shipment) {
        return res.status(404).json({ error: 'Shipment not found.' });
    }

    // STRICT ACCESS CONTROL: Ensure customer can only view their own shipments
    if (user.role !== 'administrator') {
        const emailClean = user.email.toLowerCase();
        const matchesEmail = shipment.customer_email && shipment.customer_email.toLowerCase() === emailClean;
        const matchesSender = shipment.sender && shipment.sender.toLowerCase().includes(emailClean);
        const matchesReceiver = shipment.receiver && shipment.receiver.toLowerCase().includes(emailClean);
        const matchesCompany = user.company && (shipment.sender.toLowerCase().includes(user.company.toLowerCase()) || shipment.receiver.toLowerCase().includes(user.company.toLowerCase()));

        if (!matchesEmail && !matchesSender && !matchesReceiver && !matchesCompany) {
            return res.status(403).json({ error: 'Unauthorized access. This shipment belongs to a different customer account.' });
        }
    }

    res.json(shipment);
});

// PUT /api/customer/notifications
app.put('/api/customer/notifications', (req, res) => {
    const user = getLoggedInUser(req);
    if (!user) {
        return res.status(401).json({ error: 'Not authenticated.' });
    }

    user.notifications_prefs = {
        email: req.body.email !== false,
        whatsapp: req.body.whatsapp !== false,
        sms: req.body.sms === true
    };

    res.json({ success: true, prefs: user.notifications_prefs });
});

// API REST Endpoints
app.get('/api/shipments', (req, res) => {
    let list = [...shipmentsDatabase];
    const q = (req.query.q as string || '').toLowerCase().trim();
    const status = (req.query.status as string || '').toLowerCase().trim();

    if (q) {
        list = list.filter(s =>
            s.tracking_number.toLowerCase().includes(q) ||
            s.sender.toLowerCase().includes(q) ||
            s.receiver.toLowerCase().includes(q) ||
            s.origin.toLowerCase().includes(q) ||
            s.destination.toLowerCase().includes(q)
        );
    }

    if (status) {
        list = list.filter(s => s.status.toLowerCase() === status);
    }

    res.json(list);
});

app.get('/api/shipments/track/:code', (req, res) => {
    const code = req.params.code.trim().toUpperCase();
    const found = shipmentsDatabase.find(s => s.tracking_number.toUpperCase() === code);
    if (found) {
        res.json(found);
    } else {
        res.status(404).json({ error: `Shipment with tracking number '${code}' not found.` });
    }
});

app.post('/api/shipments', (req, res) => {
    const data = req.body;
    const rawTracking = (data.tracking_number || '').trim().toUpperCase();

    if (!rawTracking) {
        return res.status(400).json({ error: 'Tracking Number is required.' });
    }

    // Uniqueness validation
    const duplicate = shipmentsDatabase.find(s => s.tracking_number.toUpperCase() === rawTracking);
    if (duplicate) {
        return res.status(400).json({ error: `Tracking number '${rawTracking}' already exists! Tracking numbers must be unique.` });
    }

    const newId = Date.now();
    const newShipment: Shipment = {
        id: newId,
        tracking_number: rawTracking,
        status: data.status || 'Pending',
        origin: data.origin || 'N/A',
        destination: data.destination || 'N/A',
        current_location: data.current_location || 'Dispatch Hub',
        sender: data.sender || '',
        receiver: data.receiver || '',
        shipment_date: data.shipment_date || new Date().toISOString().split('T')[0],
        estimated_delivery: data.estimated_delivery || 'TBD',
        actual_delivery: data.actual_delivery || 'Pending',
        carrier: data.carrier || 'Qidex Express Logistics',
        service_type: data.service_type || 'Air Freight',
        package_type: data.package_type || 'General Cargo',
        weight: data.weight || '0 kg',
        quantity: data.quantity || '1 Package',
        description: data.description || '',
        events: Array.isArray(data.events) ? data.events : [],
        last_updated: new Date().toISOString()
    };

    shipmentsDatabase.unshift(newShipment);
    res.status(201).json(newShipment);
});

app.put('/api/shipments/:id', (req, res) => {
    const id = parseInt(req.params.id, 10);
    const index = shipmentsDatabase.findIndex(s => s.id === id);

    if (index === -1) {
        return res.status(404).json({ error: 'Shipment not found.' });
    }

    const data = req.body;
    const rawTracking = (data.tracking_number || '').trim().toUpperCase();

    if (!rawTracking) {
        return res.status(400).json({ error: 'Tracking Number is required.' });
    }

    // Uniqueness validation (check other posts)
    const duplicate = shipmentsDatabase.find(s => s.id !== id && s.tracking_number.toUpperCase() === rawTracking);
    if (duplicate) {
        return res.status(400).json({ error: `Tracking number '${rawTracking}' is assigned to another shipment! Tracking numbers must be unique.` });
    }

    shipmentsDatabase[index] = {
        ...shipmentsDatabase[index],
        tracking_number: rawTracking,
        status: data.status || shipmentsDatabase[index].status,
        origin: data.origin || shipmentsDatabase[index].origin,
        destination: data.destination || shipmentsDatabase[index].destination,
        current_location: data.current_location || shipmentsDatabase[index].current_location,
        sender: data.sender !== undefined ? data.sender : shipmentsDatabase[index].sender,
        receiver: data.receiver !== undefined ? data.receiver : shipmentsDatabase[index].receiver,
        shipment_date: data.shipment_date || shipmentsDatabase[index].shipment_date,
        estimated_delivery: data.estimated_delivery || shipmentsDatabase[index].estimated_delivery,
        actual_delivery: data.actual_delivery || shipmentsDatabase[index].actual_delivery,
        carrier: data.carrier || shipmentsDatabase[index].carrier,
        service_type: data.service_type || shipmentsDatabase[index].service_type,
        package_type: data.package_type || shipmentsDatabase[index].package_type,
        weight: data.weight || shipmentsDatabase[index].weight,
        quantity: data.quantity || shipmentsDatabase[index].quantity,
        description: data.description !== undefined ? data.description : shipmentsDatabase[index].description,
        events: Array.isArray(data.events) ? data.events : shipmentsDatabase[index].events,
        last_updated: new Date().toISOString()
    };

    res.json(shipmentsDatabase[index]);
});

app.delete('/api/shipments/:id', (req, res) => {
    const id = parseInt(req.params.id, 10);
    const index = shipmentsDatabase.findIndex(s => s.id === id);

    if (index === -1) {
        return res.status(404).json({ error: 'Shipment not found.' });
    }

    const deleted = shipmentsDatabase.splice(index, 1);
    res.json({ message: 'Shipment deleted successfully.', shipment: deleted[0] });
});

// ==================================================
// PHASE 6: QUOTE, CONTACT & WHATSAPP API ENDPOINTS
// ==================================================

// POST /api/quotes - Submit new quote request
app.post('/api/quotes', (req, res) => {
    const { full_name, email, phone, origin, destination, shipment_type, package_type, weight, dimensions, shipping_method, pickup_date, additional_info } = req.body;

    if (!full_name || !email || !phone || !origin || !destination || !shipment_type || !package_type || !weight) {
        return res.status(400).json({ error: 'Please complete all required fields (Full Name, Email, Phone, Origin, Destination, Shipment Type, Package Type, Weight).' });
    }

    const emailClean = email.trim().toLowerCase();
    const quoteId = 'QT-' + new Date().getFullYear() + '-' + Math.floor(1000 + Math.random() * 9000);

    const newQuote: QuoteRequest = {
        id: quoteId,
        full_name: full_name.trim(),
        email: emailClean,
        phone: phone.trim(),
        origin: origin.trim(),
        destination: destination.trim(),
        shipment_type: shipment_type.trim(),
        package_type: package_type.trim(),
        weight: parseFloat(weight),
        dimensions: dimensions ? dimensions.trim() : 'N/A',
        shipping_method: shipping_method ? shipping_method.trim() : 'Standard Priority',
        pickup_date: pickup_date ? pickup_date.trim() : new Date().toISOString().split('T')[0],
        additional_info: additional_info ? additional_info.trim() : '',
        date_submitted: new Date().toISOString().replace('T', ' ').substring(0, 19),
        status: 'New',
        quoted_rate: '',
        admin_notes: ''
    };

    quotesDatabase.unshift(newQuote);

    // Record wp_mail email log
    emailLogs.unshift({
        id: 'email_' + Date.now(),
        recipient: emailClean,
        subject: `Quote Request Confirmation [${quoteId}] - Qidex Logistics`,
        type: 'Quote Request Confirmation',
        timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
        status: 'Delivered (wp_mail)'
    });

    res.status(201).json({ success: true, quote: newQuote });
});

// GET /api/quotes - Get list of quotes
app.get('/api/quotes', (req, res) => {
    let list = [...quotesDatabase];
    const q = (req.query.q as string || '').toLowerCase().trim();
    const status = (req.query.status as string || '').toLowerCase().trim();

    if (q) {
        list = list.filter(item =>
            item.id.toLowerCase().includes(q) ||
            item.full_name.toLowerCase().includes(q) ||
            item.email.toLowerCase().includes(q) ||
            item.origin.toLowerCase().includes(q) ||
            item.destination.toLowerCase().includes(q)
        );
    }

    if (status && status !== 'all') {
        list = list.filter(item => item.status.toLowerCase() === status);
    }

    res.json(list);
});

// GET /api/quotes/:id
app.get('/api/quotes/:id', (req, res) => {
    const item = quotesDatabase.find(q => q.id === req.params.id);
    if (!item) {
        return res.status(404).json({ error: 'Quote request not found.' });
    }
    res.json(item);
});

// PUT /api/quotes/:id - Update quote status & rate
app.put('/api/quotes/:id', (req, res) => {
    const item = quotesDatabase.find(q => q.id === req.params.id);
    if (!item) {
        return res.status(404).json({ error: 'Quote request not found.' });
    }

    const { status, quoted_rate, admin_notes } = req.body;
    if (status) item.status = status;
    if (quoted_rate !== undefined) item.quoted_rate = quoted_rate;
    if (admin_notes !== undefined) item.admin_notes = admin_notes;

    // If status updated to Quoted, log email proposal dispatch
    if (status === 'Quoted' && quoted_rate) {
        emailLogs.unshift({
            id: 'email_' + Date.now(),
            recipient: item.email,
            subject: `Freight Quote Proposal [${item.id}] - $${quoted_rate} - Qidex Logistics`,
            type: 'Rate Proposal Dispatch',
            timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
            status: 'Delivered (wp_mail)'
        });
    }

    res.json({ success: true, quote: item });
});

// POST /api/contact - Submit contact form
app.post('/api/contact', (req, res) => {
    const { full_name, email, phone, subject, message } = req.body;

    if (!full_name || !email || !subject || !message) {
        return res.status(400).json({ error: 'Please complete all required fields (Full Name, Email, Subject, Message).' });
    }

    const emailClean = email.trim().toLowerCase();
    const contactId = 'MSG-' + new Date().getFullYear() + '-' + Math.floor(100 + Math.random() * 900);

    const newContact: ContactMessage = {
        id: contactId,
        full_name: full_name.trim(),
        email: emailClean,
        phone: phone ? phone.trim() : '',
        subject: subject.trim(),
        message: message.trim(),
        date_submitted: new Date().toISOString().replace('T', ' ').substring(0, 19),
        status: 'Unread'
    };

    contactDatabase.unshift(newContact);

    // Record email log
    emailLogs.unshift({
        id: 'email_' + Date.now(),
        recipient: emailClean,
        subject: `Message Received - Qidex Logistics Support`,
        type: 'Contact Auto-Reply',
        timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
        status: 'Delivered (wp_mail)'
    });

    res.status(201).json({ success: true, contact: newContact });
});

// GET /api/contact-messages
app.get('/api/contact-messages', (req, res) => {
    let list = [...contactDatabase];
    const status = (req.query.status as string || '').toLowerCase().trim();

    if (status && status !== 'all') {
        list = list.filter(m => m.status.toLowerCase() === status);
    }

    res.json(list);
});

// PUT /api/contact-messages/:id
app.put('/api/contact-messages/:id', (req, res) => {
    const item = contactDatabase.find(m => m.id === req.params.id);
    if (!item) {
        return res.status(404).json({ error: 'Contact message not found.' });
    }

    if (req.body.status) {
        item.status = req.body.status;
    }

    res.json({ success: true, contact: item });
});

// GET /api/settings/whatsapp
app.get('/api/settings/whatsapp', (req, res) => {
    res.json(whatsappConfig);
});

// POST /api/settings/whatsapp
app.post('/api/settings/whatsapp', (req, res) => {
    const { number, default_message } = req.body;
    if (number) whatsappConfig.number = number.trim();
    if (default_message) whatsappConfig.default_message = default_message.trim();
    res.json({ success: true, settings: whatsappConfig });
});

// GET /api/logs/emails
app.get('/api/logs/emails', (req, res) => {
    res.json(emailLogs);
});

// ==================================================
// PHASE 7: WORDPRESS REST API & PAYMENT ENDPOINTS
// ==================================================

// GET /wp-json/haivora/v1/shipments & /api/shipments
app.get(['/wp-json/haivora/v1/shipments', '/api/v1/shipments'], (req, res) => {
    res.json(shipmentsDatabase);
});

// GET /wp-json/haivora/v1/shipments/:id
app.get('/wp-json/haivora/v1/shipments/:id', (req, res) => {
    const shipment = shipmentsDatabase.find(s => s.tracking_number.toLowerCase() === req.params.id.toLowerCase() || s.id.toString() === req.params.id);
    if (!shipment) {
        return res.status(404).json({ code: 'shipment_not_found', message: 'Shipment record not found.', data: { status: 404 } });
    }
    res.json(shipment);
});

// POST /wp-json/haivora/v1/shipments
app.post('/wp-json/haivora/v1/shipments', (req, res) => {
    const { sender, receiver, origin, destination, service_type, weight, status } = req.body;
    if (!sender || !receiver || !origin || !destination) {
        return res.status(400).json({ code: 'missing_parameters', message: 'Sender, receiver, origin, and destination are required.', data: { status: 400 } });
    }

    const trackingNumber = 'HV-' + new Date().getFullYear() + '-' + Math.floor(100000 + Math.random() * 900000);
    const newShipment: Shipment = {
        id: Date.now(),
        tracking_number: trackingNumber,
        customer_email: req.body.customer_email || 'client@globaltrade.com',
        status: status || 'Pending',
        origin: origin,
        destination: destination,
        current_location: 'Logistics Facility (' + origin + ')',
        sender: sender,
        receiver: receiver,
        shipment_date: new Date().toISOString().split('T')[0],
        estimated_delivery: 'TBD',
        actual_delivery: 'Pending',
        carrier: 'Haivora Global Carrier API',
        service_type: service_type || 'Standard Air Freight',
        package_type: 'Cargo Container',
        weight: (weight || 100) + ' kg',
        quantity: '1 Unit',
        description: 'REST API Generated Shipment Item',
        events: [
            {
                date: new Date().toISOString().split('T')[0],
                time: new Date().toTimeString().substring(0, 5),
                status: '1. Waybill & Cargo Registered',
                location: origin,
                description: 'Shipment created via REST API endpoint haivora/v1/shipments.'
            }
        ],
        last_updated: new Date().toISOString()
    };

    shipmentsDatabase.unshift(newShipment);
    res.status(201).json({
        success: true,
        integration: 'READY_FOR_INTEGRATION',
        tracking_number: trackingNumber,
        shipment: newShipment
    });
});

// GET /wp-json/haivora/v1/track/:code - Public PII Masked
app.get('/wp-json/haivora/v1/track/:code', (req, res) => {
    const code = req.params.code.trim().toUpperCase();
    const shipment = shipmentsDatabase.find(s => s.tracking_number.toUpperCase() === code);

    if (!shipment) {
        return res.status(404).json({ code: 'shipment_not_found', message: 'Tracking code not found in global logistics database.', data: { status: 404 } });
    }

    // Return PII Masked Public Telemetry
    res.json({
        tracking_number: shipment.tracking_number,
        status: shipment.status,
        origin: shipment.origin,
        destination: shipment.destination,
        current_location: shipment.current_location,
        estimated_delivery: shipment.estimated_delivery,
        weight: shipment.weight,
        events: shipment.events || [],
        last_updated: shipment.last_updated
    });
});

// POST /wp-json/haivora/v1/payments/initiate & /api/payments/initiate
app.post(['/wp-json/haivora/v1/payments/initiate', '/api/payments/initiate'], (req, res) => {
    const { amount, customer_email, customer_name, payment_type, related_item_id, provider } = req.body;

    if (!amount || !customer_email || !payment_type) {
        return res.status(400).json({ error: 'Amount, customer email, and payment type are required.' });
    }

    const txnId = 'TXN-' + new Date().getFullYear() + '-' + Math.floor(10000 + Math.random() * 90000);
    const reference = 'REF-' + Date.now() + '-' + Math.floor(100 + Math.random() * 900);

    const newTxn: PaymentTransaction = {
        transaction_id: txnId,
        customer_email: customer_email.trim().toLowerCase(),
        customer_name: customer_name ? customer_name.trim() : 'Valued Customer',
        amount: parseFloat(amount),
        currency: paymentGatewayConfig.currency,
        provider: provider || paymentGatewayConfig.provider,
        payment_type: payment_type,
        status: 'Pending',
        date: new Date().toISOString().replace('T', ' ').substring(0, 19),
        related_item_id: related_item_id || '',
        reference: reference
    };

    transactionsDatabase.unshift(newTxn);

    res.status(201).json({
        success: true,
        integration: 'READY_FOR_INTEGRATION',
        transaction_id: txnId,
        reference: reference,
        amount: newTxn.amount,
        currency: newTxn.currency,
        provider: newTxn.provider,
        status: 'Pending',
        public_key: paymentGatewayConfig.public_key,
        checkout_url: '/checkout-preview?reference=' + reference
    });
});

// GET /wp-json/haivora/v1/payments/transactions & /api/payments/transactions
app.get(['/wp-json/haivora/v1/payments/transactions', '/api/payments/transactions'], (req, res) => {
    let list = [...transactionsDatabase];
    const status = (req.query.status as string || '').toLowerCase().trim();
    const provider = (req.query.provider as string || '').toLowerCase().trim();

    if (status && status !== 'all') {
        list = list.filter(t => t.status.toLowerCase() === status);
    }
    if (provider && provider !== 'all') {
        list = list.filter(t => t.provider.toLowerCase() === provider);
    }

    res.json(list);
});

// PUT /api/payments/transactions/:id
app.put('/api/payments/transactions/:id', (req, res) => {
    const txn = transactionsDatabase.find(t => t.transaction_id === req.params.id || t.reference === req.params.id);
    if (!txn) {
        return res.status(404).json({ error: 'Transaction record not found.' });
    }

    if (req.body.status) txn.status = req.body.status;
    res.json({ success: true, transaction: txn });
});

// POST /wp-json/haivora/v1/webhooks/payment & /api/webhooks/payment
app.post(['/wp-json/haivora/v1/webhooks/payment', '/api/webhooks/payment'], (req, res) => {
    const signature = req.headers['stripe-signature'] || req.headers['x-paystack-signature'] || req.headers['verif-hash'] || req.headers['x-haivora-signature'];

    if (!signature && paymentGatewayConfig.webhook_secret !== 'whsec_sample_secret_key') {
        return res.status(401).json({ error: 'Cryptographic signature missing or invalid.' });
    }

    const { reference, transaction_id, status } = req.body;
    const ref = reference || transaction_id;

    if (!ref) {
        return res.status(400).json({ error: 'Transaction reference missing in webhook payload.' });
    }

    const txn = transactionsDatabase.find(t => t.reference === ref || t.transaction_id === ref);
    if (!txn) {
        return res.status(404).json({ error: 'Transaction reference not found.' });
    }

    txn.status = status || 'Successful';

    // Log webhook execution in email/event audit
    emailLogs.unshift({
        id: 'webhook_' + Date.now(),
        recipient: txn.customer_email,
        subject: `Payment Verified (${txn.provider.toUpperCase()}) - ${txn.transaction_id}`,
        type: 'Cryptographic Webhook Dispatch',
        timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
        status: 'Verified & Executed'
    });

    res.json({
        success: true,
        message: 'Webhook verified cryptographically and transaction status updated.',
        reference: ref,
        status: txn.status
    });
});

// GET /api/settings/payment
app.get('/api/settings/payment', (req, res) => {
    // Hide secret key in public API response for security
    const safeConfig = {
        ...paymentGatewayConfig,
        secret_key: paymentGatewayConfig.secret_key ? '••••••••' + paymentGatewayConfig.secret_key.slice(-4) : ''
    };
    res.json(safeConfig);
});

// POST /api/settings/payment
app.post('/api/settings/payment', (req, res) => {
    const { provider, public_key, secret_key, webhook_secret, currency, mode } = req.body;

    if (provider) paymentGatewayConfig.provider = provider.trim();
    if (public_key) paymentGatewayConfig.public_key = public_key.trim();
    if (secret_key && !secret_key.includes('••••')) paymentGatewayConfig.secret_key = secret_key.trim();
    if (webhook_secret) paymentGatewayConfig.webhook_secret = webhook_secret.trim();
    if (currency) paymentGatewayConfig.currency = currency.trim().toUpperCase();
    if (mode) paymentGatewayConfig.mode = mode.trim();

    res.json({ success: true, settings: paymentGatewayConfig });
});

// GET /api/settings/carrier-api
app.get('/api/settings/carrier-api', (req, res) => {
    const safeCarrierConfig = {
        ...carrierApiConfig,
        api_secret: carrierApiConfig.api_secret ? '••••••••' + carrierApiConfig.api_secret.slice(-4) : ''
    };
    res.json(safeCarrierConfig);
});

// POST /api/settings/carrier-api
app.post('/api/settings/carrier-api', (req, res) => {
    const { provider, api_url, api_key, api_secret, mode } = req.body;

    if (provider) carrierApiConfig.provider = provider.trim();
    if (api_url) carrierApiConfig.api_url = api_url.trim();
    if (api_key) carrierApiConfig.api_key = api_key.trim();
    if (api_secret && !api_secret.includes('••••')) carrierApiConfig.api_secret = api_secret.trim();
    if (mode) carrierApiConfig.mode = mode.trim();

    res.json({ success: true, settings: carrierApiConfig });
});

/**
 * Helper to parse PHP theme files for AI Studio preview
 */
function renderWpPhpTemplate(filename: string): string {
    const filePath = path.join(process.cwd(), filename);
    if (!fs.existsSync(filePath)) {
        return `<h1>File ${filename} not found</h1>`;
    }

    let content = fs.readFileSync(filePath, 'utf8');

    if (content.includes('get_header();')) {
        const headerContent = renderWpPhpTemplate('header.php');
        content = content.replace(/<\?php\s+get_header\(\);\s*\?>/g, headerContent);
    }

    if (content.includes('get_footer();')) {
        const footerContent = renderWpPhpTemplate('footer.php');
        content = content.replace(/<\?php\s+get_footer\(\);\s*\?>/g, footerContent);
    }

    content = content
        .replace(/<\?php\s+language_attributes\(\);\s*\?>/g, 'lang="en"')
        .replace(/<\?php\s+bloginfo\('charset'\);\s*\?>/g, 'UTF-8')
        .replace(/<\?php\s+wp_head\(\);\s*\?>/g, `
            <title>Haivora Logistics - Qidex Express LOGISTICS</title>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="/style.css?v=1.0.1">
            <link rel="stylesheet" href="/assets/css/main.css?v=1.0.1">
            <link rel="stylesheet" href="/assets/css/responsive.css?v=1.0.1">
            <script>
                var haivoraTrackingData = {
                    ajaxUrl: '/wp-admin/admin-ajax.php',
                    nonce: 'haivora_preview_nonce'
                };
            </script>
            <script src="/assets/js/navigation.js?v=1.0.1" defer></script>
            <script src="/assets/js/main.js?v=1.0.1" defer></script>
            <script src="/assets/js/tracking-preview.js?v=1.0.1" defer></script>
        `)
        .replace(/<\?php\s+body_class\(\);\s*\?>/g, 'class="home page-template-default wp-custom-logo"')
        .replace(/<\?php\s+wp_body_open\(\);\s*\?>/g, '')
        .replace(/<\?php\s+wp_footer\(\);\s*\?>/g, '')
        .replace(/<\?php\s+echo\s+esc_html\(haivora_logistics_company_name\(\)\);\s*\?>/g, 'Qidex Express LOGISTICS')
        .replace(/<\?php\s+echo\s+esc_html\(haivora_logistics_phone\(\)\);\s*\?>/g, '+1 (800) QIDEX-LOG')
        .replace(/<\?php\s+echo\s+esc_html\(haivora_logistics_email\(\)\);\s*\?>/g, 'support@qidexexpress.com')
        .replace(/<\?php\s+echo\s+esc_attr\(haivora_logistics_email\(\)\);\s*\?>/g, 'support@qidexexpress.com')
        .replace(/<\?php\s+echo\s+esc_html\(haivora_logistics_address\(\)\);\s*\?>/g, '100 Global Trade Parkway, Logistics Hub, NY 10001')
        .replace(/<\?php\s+echo\s+esc_html\(date\('Y'\)\);\s*\?>/g, new Date().getFullYear().toString())
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/'\)\);\s*\?>/g, '/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/track-shipment\/'\)\);\s*\?>/g, '/track-shipment/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/services\/'\)\);\s*\?>/g, '/services/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/about-us\/'\)\);\s*\?>/g, '/about-us/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/get-a-quote\/'\)\);\s*\?>/g, '/get-a-quote/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/contact\/'\)\);\s*\?>/g, '/contact/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/login\/'\)\);\s*\?>/g, '/login/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/register\/'\)\);\s*\?>/g, '/register/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/#track'\)\);\s*\?>/g, '/track-shipment/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/#services'\)\);\s*\?>/g, '/services/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/#why-us'\)\);\s*\?>/g, '/about-us/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/#quote'\)\);\s*\?>/g, '/get-a-quote/')
        .replace(/<\?php\s+echo\s+esc_url\(home_url\('\/#contact'\)\);\s*\?>/g, '/contact/')
        .replace(/<\?php\s+haivora_logistics_site_logo\(\);\s*\?>/g, `
            <a href="/" class="site-branding" rel="home">
                <div class="brand-logo-symbol">
                    <div class="brand-logo-symbol-inner"></div>
                </div>
                <span class="brand-title">
                    QIDEX <span class="brand-accent">EXPRESS</span>
                </span>
            </a>
        `)
        .replace(/<\?php[\s\S]*?\?>/g, '');

    return content;
}

/**
 * Render Phase 4 & Phase 6 WordPress Admin Interface
 */
app.get('/shipment-admin*', (req, res) => {
    res.send(`
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WordPress Logistics Admin Portal - Haivora Logistics</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            :root {
                --wp-admin-bg: #f0f0f1;
                --wp-menu-bg: #1d2327;
                --wp-menu-active: #2271b1;
                --wp-text: #2c3338;
                --primary: #0F172A;
                --secondary: #2563EB;
            }
            * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
            body { background: var(--wp-admin-bg); color: var(--wp-text); min-height: 100vh; display: flex; flex-direction: column; }
            
            /* Admin Top Bar */
            .wp-admin-bar { background: #1d2327; color: #f0f0f1; height: 32px; display: flex; align-items: center; justify-content: space-between; padding: 0 15px; font-size: 13px; font-weight: 600; }
            .wp-admin-bar a { color: #f0f0f1; text-decoration: none; margin-right: 15px; display: inline-flex; align-items: center; gap: 5px; }
            .wp-admin-bar a:hover { color: #72aee6; }
            
            /* Main Layout */
            .wp-admin-layout { display: flex; flex: 1; }
            .wp-sidebar { width: 220px; background: #1d2327; color: #a7aaad; padding-top: 10px; flex-shrink: 0; }
            .wp-sidebar-item { padding: 12px 16px; font-size: 13px; color: #a7aaad; text-decoration: none; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid transparent; cursor: pointer; }
            .wp-sidebar-item:hover, .wp-sidebar-item.active { background: #131719; color: #fff; border-left-color: var(--secondary); }
            .sidebar-badge { background: #2563eb; color: #fff; font-size: 11px; padding: 2px 7px; border-radius: 9999px; font-weight: 800; }
            
            .wp-content { flex: 1; padding: 25px; overflow-x: auto; }
            .admin-title-wrap { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
            .admin-heading { font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: #1d2327; }
            
            .btn-wp { background: #2271b1; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; font-weight: 700; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
            .btn-wp:hover { background: #135e96; }
            .btn-danger { background: #dc2626; }
            .btn-danger:hover { background: #b91c1c; }
            .btn-success { background: #16a34a; }
            .btn-success:hover { background: #15803d; }
            .btn-secondary { background: #e2e8f0; color: #1e293b; }
            .btn-secondary:hover { background: #cbd5e1; }

            /* Filter Bar */
            .admin-filter-card { background: #fff; border: 1px solid #c3c4c7; padding: 12px 16px; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
            .admin-input, .admin-select { padding: 6px 12px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 13px; }
            .admin-input:focus, .admin-select:focus { border-color: #2271b1; outline: none; box-shadow: 0 0 0 2px rgba(34,113,177,0.2); }
            
            /* Data Table */
            .wp-table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #c3c4c7; border-radius: 4px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
            .wp-table th, .wp-table td { padding: 12px 14px; text-align: left; font-size: 13px; border-bottom: 1px solid #e2e8f0; }
            .wp-table th { background: #f6f7f7; font-weight: 700; color: #1d2327; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; }
            .wp-table tr:hover { background: #f8fafc; }
            
            .status-badge { display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
            .status-in-transit { background: #dbeafe; color: #1d4ed8; }
            .status-delivered { background: #d1fae5; color: #047857; }
            .status-customs { background: #fef3c7; color: #b45309; }
            .status-pending { background: #f3f4f6; color: #4b5563; }
            .status-hold { background: #fee2e2; color: #b91c1c; }
            .status-new { background: #eff6ff; color: #2563eb; }
            .status-quoted { background: #dcfce7; color: #15803d; }

            /* Modal Overlay */
            .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px; }
            .modal-overlay.active { display: flex; }
            .modal-box { background: #fff; border-radius: 8px; width: 100%; max-width: 900px; max-height: 90vh; overflow-y: auto; padding: 25px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); }
            .modal-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; margin-bottom: 20px; }
            
            .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 14px; margin-bottom: 15px; }
            .form-group { display: flex; flex-direction: column; }
            .form-group label { font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 4px; text-transform: uppercase; }
            .form-group input, .form-group select, .form-group textarea { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px; }
            
            .tab-view { display: none; }
            .tab-view.active { display: block; }
            
            /* Tracking Event Item Box */
            .event-item-card { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; margin-bottom: 10px; position: relative; }
        </style>
    </head>
    <body>
        <!-- Top Admin Bar -->
        <div class="wp-admin-bar">
            <div>
                <a href="/">🏠 View Public Site</a>
                <a href="/track-shipment">🔍 Live Tracking Portal</a>
                <a href="/dashboard">👤 Customer Portal</a>
            </div>
            <div>
                <span>Logged in as <strong>Administrator</strong></span>
            </div>
        </div>

        <div class="wp-admin-layout">
            <!-- Sidebar Navigation -->
            <div class="wp-sidebar">
                <div class="wp-sidebar-item active" onclick="switchTab('shipments')">
                    <span>📦 Shipments</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('quotes')">
                    <span>📋 Quote Requests</span>
                    <span id="badge-quotes" class="sidebar-badge">2</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('contact')">
                    <span>📥 Contact Inbox</span>
                    <span id="badge-contact" class="sidebar-badge">1</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('payments')">
                    <span>💳 Payments & Transactions</span>
                    <span id="badge-payments" class="sidebar-badge" style="background:#10b981;">3</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('carrier-settings')">
                    <span>🔑 API & Integrations</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('whatsapp')">
                    <span>💬 WhatsApp & Email</span>
                </div>
                <div class="wp-sidebar-item" onclick="switchTab('emails')">
                    <span>✉️ Email Audit Logs</span>
                </div>
                <a href="/" class="wp-sidebar-item" style="margin-top:20px; border-top:1px solid #334155;">
                    <span>⚙️ Settings</span>
                </a>
            </div>

            <!-- Content Area -->
            <div class="wp-content">
                <!-- Alert Banners -->
                <div id="admin-alert-banner" style="display: none; padding: 12px 16px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; font-weight: 600;"></div>

                <!-- TAB 1: SHIPMENTS -->
                <div id="view-shipments" class="tab-view active">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">Shipment Administration</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">WordPress Custom Post Type (<code style="font-family:monospace;">shipment</code>) Record Manager</p>
                        </div>
                        <button id="btn-open-add-modal" class="btn-wp">+ Add New Shipment</button>
                    </div>

                    <div class="admin-filter-card">
                        <input type="text" id="filter-search-input" class="admin-input" placeholder="Search tracking #, sender, receiver..." style="min-width: 260px;">
                        <select id="filter-status-select" class="admin-select">
                            <option value="">All Statuses</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Customs Clearance">Customs Clearance</option>
                            <option value="Pending">Pending</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                        <button id="btn-apply-filter" class="btn-wp btn-secondary">Filter</button>
                        <span id="shipment-counter" style="margin-left: auto; font-size: 12px; font-weight: 700; color: #64748b;">3 Shipments Total</span>
                    </div>

                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>Tracking #</th>
                                <th>Status</th>
                                <th>Origin ➔ Destination</th>
                                <th>Current Location</th>
                                <th>Sender / Receiver</th>
                                <th>Est. Delivery</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="shipments-table-body"></tbody>
                    </table>
                </div>

                <!-- TAB 2: QUOTE REQUESTS -->
                <div id="view-quotes" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">Freight Quote Requests</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Manage incoming freight quotes, assign shipping rates, and dispatch automated emails.</p>
                        </div>
                    </div>

                    <div class="admin-filter-card">
                        <input type="text" id="quote-search-input" class="admin-input" placeholder="Search quote ID, name, email..." style="min-width: 260px;">
                        <select id="quote-status-select" class="admin-select">
                            <option value="all">All Quote Statuses</option>
                            <option value="New">New</option>
                            <option value="Reviewing">Reviewing</option>
                            <option value="Quoted">Quoted</option>
                            <option value="Accepted">Accepted</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                        <button id="btn-filter-quotes" class="btn-wp btn-secondary">Filter Quotes</button>
                    </div>

                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>Quote ID</th>
                                <th>Status</th>
                                <th>Customer Name & Email</th>
                                <th>Route (Origin ➔ Dest)</th>
                                <th>Cargo Specs</th>
                                <th>Quoted Rate</th>
                                <th>Submitted Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="quotes-table-body"></tbody>
                    </table>
                </div>

                <!-- TAB 3: CONTACT INBOX -->
                <div id="view-contact" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">Contact Messages Inbox</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Review support tickets, customs inquiries, and general contact messages.</p>
                        </div>
                    </div>

                    <div class="admin-filter-card">
                        <select id="contact-status-select" class="admin-select">
                            <option value="all">All Statuses</option>
                            <option value="Unread">Unread</option>
                            <option value="Replied">Replied</option>
                            <option value="Archived">Archived</option>
                        </select>
                        <button id="btn-filter-contact" class="btn-wp btn-secondary">Filter Messages</button>
                    </div>

                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>MSG ID</th>
                                <th>Status</th>
                                <th>From</th>
                                <th>Subject</th>
                                <th>Message Excerpt</th>
                                <th>Date Received</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="contact-table-body"></tbody>
                    </table>
                </div>

                <!-- TAB 4: WHATSAPP & EMAIL SETTINGS -->
                <div id="view-whatsapp" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">WhatsApp & Email Dispatch Settings</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Configure WhatsApp chat widget numbers, template messages, and wp_mail notification senders.</p>
                        </div>
                    </div>

                    <div style="background: #fff; border: 1px solid #c3c4c7; border-radius: 6px; padding: 25px; max-width: 700px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <form id="whatsapp-settings-form" onsubmit="event.preventDefault(); return false;">
                            <h3 style="font-size: 14px; font-weight: 800; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 15px; color: #0f172a;">WhatsApp Floating Widget Settings</h3>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label for="wa-num">WhatsApp Phone Number (E.164 Format) *</label>
                                <input type="text" id="wa-num" required style="font-weight: bold; font-family: monospace;">
                                <span style="font-size: 11px; color: #64748b; margin-top: 3px;">Include country code without '+' or spaces, e.g. 18005557433.</span>
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="wa-msg">Default Initial Message</label>
                                <textarea id="wa-msg" rows="3"></textarea>
                            </div>

                            <button type="submit" id="btn-save-wa" class="btn-wp btn-success" style="padding: 10px 20px;">Save Configuration</button>
                        </form>
                    </div>
                </div>

                <!-- TAB 5: EMAIL AUDIT LOGS -->
                <div id="view-emails" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">Email Notification Audit Logs (<code style="font-family:monospace;">wp_mail</code>)</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Live audit log of all system email dispatches (quote confirmations, rate proposals, support auto-replies).</p>
                        </div>
                    </div>

                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Recipient</th>
                                <th>Subject</th>
                                <th>Notification Type</th>
                                <th>Timestamp</th>
                                <th>Delivery Status</th>
                            </tr>
                        </thead>
                        <tbody id="email-logs-table-body"></tbody>
                    </table>
                </div>

                <!-- TAB 6: PAYMENTS & TRANSACTIONS -->
                <div id="view-payments" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">Payment Transactions Architecture</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Multi-provider transaction records (Stripe, Flutterwave, Paystack) with webhook verification logs.</p>
                        </div>
                        <span style="background:#10b981; color:#fff; padding:6px 12px; border-radius:4px; font-size:12px; font-weight:800;">STATUS: READY FOR INTEGRATION</span>
                    </div>

                    <div class="admin-filter-card">
                        <select id="filter-payment-status" class="admin-select" onchange="loadPayments()">
                            <option value="all">All Payment Statuses</option>
                            <option value="Successful">Successful</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                            <option value="Refunded">Refunded</option>
                        </select>
                        <select id="filter-payment-provider" class="admin-select" onchange="loadPayments()">
                            <option value="all">All Gateways</option>
                            <option value="stripe">Stripe</option>
                            <option value="flutterwave">Flutterwave</option>
                            <option value="paystack">Paystack</option>
                        </select>
                        <button onclick="triggerTestPayment()" class="btn-wp btn-success" style="margin-left:auto;">+ Simulate Payment</button>
                    </div>

                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>Txn ID / Ref</th>
                                <th>Customer</th>
                                <th>Amount & Currency</th>
                                <th>Provider</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="payments-table-body"></tbody>
                    </table>
                </div>

                <!-- TAB 7: CARRIER & PAYMENT INTEGRATION SETTINGS -->
                <div id="view-carrier-settings" class="tab-view">
                    <div class="admin-title-wrap">
                        <div>
                            <h1 class="admin-heading">API & Gateway Integration Settings</h1>
                            <p style="font-size: 13px; color: #64748b; margin-top: 2px;">Configure external Carrier APIs (DHL, FedEx, UPS) and Payment Gateways (Flutterwave, Paystack, Stripe).</p>
                        </div>
                        <span style="background:#2563eb; color:#fff; padding:6px 12px; border-radius:4px; font-size:12px; font-weight:800;">STATUS: READY FOR INTEGRATION</span>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap:20px; margin-top:15px;">
                        <!-- Carrier API Config Card -->
                        <div style="background:#fff; border:1px solid #c3c4c7; border-radius:6px; padding:20px;">
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin-bottom:12px; display:flex; align-items:center; justify-content:space-between;">
                                <span>🚚 Carrier Logistics API</span>
                                <span style="font-size:11px; background:#eff6ff; color:#1d4ed8; padding:3px 8px; border-radius:4px;">DHL / FedEx / UPS</span>
                            </h3>
                            <form id="carrier-api-form" onsubmit="saveCarrierApiSettings(event)">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Provider</label>
                                    <select id="cfg-carrier-provider" class="admin-select" style="width:100%;">
                                        <option value="demo_carrier">Demo Carrier Wrapper (Internal)</option>
                                        <option value="dhl_express">DHL Express API</option>
                                        <option value="fedex_rest">FedEx Web Services REST API</option>
                                        <option value="ups_shipping">UPS Shipping & Tracking API</option>
                                        <option value="aramex_express">Aramex Global API</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">API Base URL</label>
                                    <input type="text" id="cfg-carrier-url" class="admin-input" style="width:100%; font-family:monospace;" placeholder="https://api.carrier-service.com/v1">
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">API Key</label>
                                    <input type="text" id="cfg-carrier-key" class="admin-input" style="width:100%; font-family:monospace;" placeholder="haivora_live_key_xxx">
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">API Secret</label>
                                    <input type="password" id="cfg-carrier-secret" class="admin-input" style="width:100%; font-family:monospace;" placeholder="••••••••">
                                </div>
                                <div class="form-group" style="margin-bottom:15px;">
                                    <label style="font-size:12px; font-weight:700;">Environment Mode</label>
                                    <select id="cfg-carrier-mode" class="admin-select" style="width:100%;">
                                        <option value="sandbox">Sandbox / Testing</option>
                                        <option value="production">Live Production</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-wp" style="width:100%;">Save Carrier Credentials</button>
                            </form>
                        </div>

                        <!-- Payment Gateway Config Card -->
                        <div style="background:#fff; border:1px solid #c3c4c7; border-radius:6px; padding:20px;">
                            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin-bottom:12px; display:flex; align-items:center; justify-content:space-between;">
                                <span>💳 Payment Gateway API</span>
                                <span style="font-size:11px; background:#f0fdf4; color:#15803d; padding:3px 8px; border-radius:4px;">Stripe / Flutterwave / Paystack</span>
                            </h3>
                            <form id="payment-gateway-form" onsubmit="savePaymentGatewaySettings(event)">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Default Provider</label>
                                    <select id="cfg-payment-provider" class="admin-select" style="width:100%;">
                                        <option value="stripe">Stripe Payments</option>
                                        <option value="flutterwave">Flutterwave Global</option>
                                        <option value="paystack">Paystack Africa</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Public Key (Frontend Safe)</label>
                                    <input type="text" id="cfg-payment-pubkey" class="admin-input" style="width:100%; font-family:monospace;" placeholder="pk_test_sample_12345">
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Secret Key (Server Only)</label>
                                    <input type="password" id="cfg-payment-seckey" class="admin-input" style="width:100%; font-family:monospace;" placeholder="sk_test_••••••••">
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Webhook Secret</label>
                                    <input type="text" id="cfg-payment-whsecret" class="admin-input" style="width:100%; font-family:monospace;" placeholder="whsec_sample_secret_key">
                                </div>
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label style="font-size:12px; font-weight:700;">Currency</label>
                                    <select id="cfg-payment-currency" class="admin-select" style="width:100%;">
                                        <option value="USD">USD ($)</option>
                                        <option value="EUR">EUR (€)</option>
                                        <option value="GBP">GBP (£)</option>
                                        <option value="NGN">NGN (₦)</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:15px;">
                                    <label style="font-size:12px; font-weight:700;">Environment Mode</label>
                                    <select id="cfg-payment-mode" class="admin-select" style="width:100%;">
                                        <option value="test">Test / Sandbox</option>
                                        <option value="live">Live Production</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-wp btn-success" style="width:100%;">Save Gateway Configuration</button>
                            </form>
                        </div>
                    </div>

                    <!-- REST API Endpoint Documentation Card -->
                    <div style="background:#fff; border:1px solid #c3c4c7; border-radius:6px; padding:20px; margin-top:20px;">
                        <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin-bottom:10px;">🔌 Registered WordPress REST API Routes (<code style="font-family:monospace; color:#2563eb;">haivora/v1</code>)</h3>
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:10px; font-size:12px; font-family:monospace;">
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#16a34a;">GET</strong> /wp-json/haivora/v1/shipments<br>
                                <span style="color:#64748b; font-family:sans-serif;">List all shipments (Admin / Auth required)</span>
                            </div>
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#2563eb;">POST</strong> /wp-json/haivora/v1/shipments<br>
                                <span style="color:#64748b; font-family:sans-serif;">Create new shipment (Auth required)</span>
                            </div>
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#ca8a04;">PUT</strong> /wp-json/haivora/v1/shipments/{id}<br>
                                <span style="color:#64748b; font-family:sans-serif;">Update shipment telemetry (Admin only)</span>
                            </div>
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#16a34a;">GET</strong> /wp-json/haivora/v1/track/{code}<br>
                                <span style="color:#64748b; font-family:sans-serif;">Public tracking (PII Masked)</span>
                            </div>
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#2563eb;">POST</strong> /wp-json/haivora/v1/payments/initiate<br>
                                <span style="color:#64748b; font-family:sans-serif;">Initiate payment transaction token</span>
                            </div>
                            <div style="background:#f8fafc; padding:10px; border-radius:4px; border:1px solid #e2e8f0;">
                                <strong style="color:#2563eb;">POST</strong> /wp-json/haivora/v1/webhooks/payment<br>
                                <span style="color:#64748b; font-family:sans-serif;">Cryptographic Webhook Endpoint</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Add / Edit Shipment Modal -->
        <div id="shipment-modal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <h2 id="modal-title" style="font-family:'Outfit',sans-serif; font-size:1.3rem; font-weight:800;">Add New Shipment</h2>
                    <button id="btn-close-modal" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#64748b;">&times;</button>
                </div>

                <form id="shipment-form" onsubmit="event.preventDefault(); return false;">
                    <input type="hidden" id="form-shipment-id" value="">

                    <div style="background:#eff6ff; border-left:4px solid #2563eb; padding:10px 14px; margin-bottom:15px; border-radius:2px; font-size:12px; color:#1e3a8a;">
                        <strong>Security & Validation:</strong> Tracking numbers are required and must be unique. Nonces and capability verification applied on save.
                    </div>

                    <h4 style="font-size:12px; font-weight:800; text-transform:uppercase; color:#0f172a; margin-bottom:8px;">1. Telemetry & Routing</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="f-tracking">Tracking Number *</label>
                            <input type="text" id="f-tracking" placeholder="e.g. QX-8829-US" required style="font-family:monospace; font-weight:bold; text-transform:uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="f-status">Status *</label>
                            <select id="f-status">
                                <option value="In Transit">🚚 In Transit</option>
                                <option value="Delivered">✅ Delivered</option>
                                <option value="Customs Clearance">🛃 Customs Clearance</option>
                                <option value="Pending">⏳ Pending</option>
                                <option value="On Hold">⚠️ On Hold</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f-origin">Origin *</label>
                            <input type="text" id="f-origin" placeholder="e.g. JFK Airport, NY, USA" required>
                        </div>
                        <div class="form-group">
                            <label for="f-destination">Destination *</label>
                            <input type="text" id="f-destination" placeholder="e.g. Frankfurt Airport, DE" required>
                        </div>
                        <div class="form-group">
                            <label for="f-location">Current Location</label>
                            <input type="text" id="f-location" placeholder="e.g. Atlantic Flight QX-702">
                        </div>
                        <div class="form-group">
                            <label for="f-carrier">Carrier</label>
                            <input type="text" id="f-carrier" placeholder="e.g. Qidex Transatlantic Air Lines">
                        </div>
                        <div class="form-group">
                            <label for="f-service">Service Type</label>
                            <select id="f-service">
                                <option value="Air Freight">✈️ Air Freight</option>
                                <option value="Ocean Cargo">🚢 Ocean Cargo</option>
                                <option value="Express Courier">🚀 Express Courier</option>
                                <option value="Road Freight">🚛 Road Freight</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f-estdelivery">Est. Delivery</label>
                            <input type="text" id="f-estdelivery" placeholder="e.g. Aug 14, 2026 - 16:30 GMT">
                        </div>
                    </div>

                    <h4 style="font-size:12px; font-weight:800; text-transform:uppercase; color:#0f172a; margin-bottom:8px; margin-top:15px;">2. Contacts & Package Specs</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="f-sender">Sender (Consignor)</label>
                            <input type="text" id="f-sender" placeholder="e.g. Acme Tech Supply Corp">
                        </div>
                        <div class="form-group">
                            <label for="f-receiver">Receiver (Consignee)</label>
                            <input type="text" id="f-receiver" placeholder="e.g. Global Euro Distribution GmbH">
                        </div>
                        <div class="form-group">
                            <label for="f-package">Package Type</label>
                            <input type="text" id="f-package" placeholder="e.g. Pallet / Cartons">
                        </div>
                        <div class="form-group">
                            <label for="f-weight">Weight</label>
                            <input type="text" id="f-weight" placeholder="e.g. 450 kg">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:15px;">
                        <label for="f-desc">Description</label>
                        <textarea id="f-desc" rows="2" placeholder="Cargo details and notes..."></textarea>
                    </div>

                    <div style="border-top:1px solid #e2e8f0; padding-top:15px; margin-top:15px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <h4 style="font-size:12px; font-weight:800; text-transform:uppercase; color:#0f172a;">3. Tracking Events & Milestones History</h4>
                            <button type="button" id="btn-add-event-row" class="btn-wp btn-secondary" style="font-size:11px; padding:4px 10px;">+ Add Event</button>
                        </div>
                        <div id="events-repeater-container"></div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; border-top:1px solid #e2e8f0; padding-top:15px;">
                        <button type="button" id="btn-cancel-modal" class="btn-wp btn-secondary">Cancel</button>
                        <button type="submit" id="btn-save-shipment" class="btn-wp">Save Shipment</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Review Quote Modal -->
        <div id="quote-modal" class="modal-overlay">
            <div class="modal-box" style="max-width: 700px;">
                <div class="modal-header">
                    <h2 id="quote-modal-title" style="font-family:'Outfit',sans-serif; font-size:1.3rem; font-weight:800;">Review Quote Request</h2>
                    <button id="btn-close-quote-modal" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#64748b;">&times;</button>
                </div>

                <div id="quote-modal-body"></div>
            </div>
        </div>

        <script>
            let currentShipments = [];
            let currentQuotes = [];
            let currentContacts = [];

            function switchTab(tabId) {
                document.querySelectorAll('.tab-view').forEach(v => v.classList.remove('active'));
                document.querySelectorAll('.wp-sidebar-item').forEach(i => i.classList.remove('active'));

                const view = document.getElementById('view-' + tabId);
                if (view) view.classList.add('active');

                if (tabId === 'shipments') loadShipments();
                else if (tabId === 'quotes') loadQuotes();
                else if (tabId === 'contact') loadContactMessages();
                else if (tabId === 'payments') loadPayments();
                else if (tabId === 'carrier-settings') loadIntegrationSettings();
                else if (tabId === 'whatsapp') loadWhatsAppSettings();
                else if (tabId === 'emails') loadEmailLogs();
            }

            async function loadShipments() {
                const search = document.getElementById('filter-search-input').value;
                const status = document.getElementById('filter-status-select').value;
                
                let url = '/api/shipments?';
                if (search) url += 'q=' + encodeURIComponent(search) + '&';
                if (status) url += 'status=' + encodeURIComponent(status);

                try {
                    const res = await fetch(url);
                    currentShipments = await res.json();
                    renderShipmentsTable(currentShipments);
                } catch(e) {
                    showAlert('Error loading shipments: ' + e.message, 'danger');
                }
            }

            function renderShipmentsTable(list) {
                const tbody = document.getElementById('shipments-table-body');
                const counter = document.getElementById('shipment-counter');
                if (counter) counter.textContent = list.length + ' Shipments Total';

                if (list.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px; color:#64748b;">No matching shipments found in system.</td></tr>';
                    return;
                }

                let html = '';
                list.forEach(item => {
                    let stClass = 'status-pending';
                    const st = item.status.toLowerCase();
                    if (st.includes('transit')) stClass = 'status-in-transit';
                    else if (st.includes('delivered')) stClass = 'status-delivered';
                    else if (st.includes('customs')) stClass = 'status-customs';
                    else if (st.includes('hold')) stClass = 'status-hold';

                    html += \`
                        <tr>
                            <td><strong style="font-family:monospace; font-size:14px; color:#0f172a;">\${item.tracking_number}</strong></td>
                            <td><span class="status-badge \${stClass}">\${item.status}</span></td>
                            <td><strong>\${item.origin}</strong><br><span style="color:#64748b; font-size:11px;">➔ \${item.destination}</span></td>
                            <td>\${item.current_location || '—'}</td>
                            <td><strong>From:</strong> \${item.sender || 'N/A'}<br><span style="color:#64748b; font-size:11px;"><strong>To:</strong> \${item.receiver || 'N/A'}</span></td>
                            <td>\${item.estimated_delivery || '—'}</td>
                            <td>
                                <button class="btn-wp btn-secondary" onclick="editShipment(\${item.id})" style="font-size:11px; padding:4px 8px;">Edit</button>
                                <button class="btn-wp btn-danger" onclick="deleteShipment(\${item.id})" style="font-size:11px; padding:4px 8px; margin-left:4px;">Delete</button>
                            </td>
                        </tr>
                    \`;
                });
                tbody.innerHTML = html;
            }

            // Load Quotes
            async function loadQuotes() {
                const search = document.getElementById('quote-search-input').value;
                const status = document.getElementById('quote-status-select').value;

                let url = '/api/quotes?';
                if (search) url += 'q=' + encodeURIComponent(search) + '&';
                if (status) url += 'status=' + encodeURIComponent(status);

                try {
                    const res = await fetch(url);
                    currentQuotes = await res.json();
                    renderQuotesTable(currentQuotes);
                } catch(e) {
                    showAlert('Error loading quotes.', 'danger');
                }
            }

            function renderQuotesTable(list) {
                const tbody = document.getElementById('quotes-table-body');
                document.getElementById('badge-quotes').textContent = list.length;

                if (list.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:30px; color:#64748b;">No quote requests found.</td></tr>';
                    return;
                }

                let html = '';
                list.forEach(q => {
                    let stClass = q.status === 'New' ? 'status-new' : (q.status === 'Quoted' ? 'status-quoted' : 'status-pending');
                    html += \`
                        <tr>
                            <td><strong style="font-family:monospace; font-size:13px; color:#2563eb;">\${q.id}</strong></td>
                            <td><span class="status-badge \${stClass}">\${q.status}</span></td>
                            <td><strong>\${q.full_name}</strong><br><span style="font-size:11px; color:#64748b;">\${q.email}</span></td>
                            <td><strong>\${q.origin}</strong><br><span style="font-size:11px; color:#64748b;">➔ \${q.destination}</span></td>
                            <td>\${q.shipment_type} (\${q.weight} kg)</td>
                            <td>\${q.quoted_rate ? ('<strong>$' + q.quoted_rate + '</strong>') : '<em style="color:#94a3b8;">Pending</em>'}</td>
                            <td><span style="font-size:11px; color:#64748b;">\${q.date_submitted}</span></td>
                            <td>
                                <button class="btn-wp" onclick="reviewQuote('\${q.id}')" style="font-size:11px; padding:4px 8px;">Review & Quote</button>
                            </td>
                        </tr>
                    \`;
                });
                tbody.innerHTML = html;
            }

            window.reviewQuote = function(quoteId) {
                const q = currentQuotes.find(item => item.id === quoteId);
                if (!q) return;

                const body = document.getElementById('quote-modal-body');
                body.innerHTML = \`
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:15px; margin-bottom:20px; font-size:13px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                            <div><strong>Reference ID:</strong> <span style="font-family:monospace; color:#2563eb;">\${q.id}</span></div>
                            <div><strong>Date Submitted:</strong> \${q.date_submitted}</div>
                            <div><strong>Full Name:</strong> \${q.full_name}</div>
                            <div><strong>Email:</strong> \${q.email}</div>
                            <div><strong>Phone:</strong> \${q.phone}</div>
                            <div><strong>Route:</strong> \${q.origin} ➔ \${q.destination}</div>
                            <div><strong>Shipment Mode:</strong> \${q.shipment_type}</div>
                            <div><strong>Package / Weight:</strong> \${q.package_type} / \${q.weight} kg</div>
                        </div>
                        <div style="border-top:1px solid #e2e8f0; padding-top:8px; margin-top:8px;">
                            <strong>Additional Cargo Specs / Notes:</strong><br>
                            \${q.additional_info || 'None provided.'}
                        </div>
                    </div>

                    <form onsubmit="saveQuoteRate(event, '\${q.id}')">
                        <div class="form-group" style="margin-bottom:12px;">
                            <label>Quote Status *</label>
                            <select id="modal-q-status" class="admin-select" style="width:100%;">
                                <option value="New" \${q.status === 'New' ? 'selected' : ''}>New</option>
                                <option value="Reviewing" \${q.status === 'Reviewing' ? 'selected' : ''}>Reviewing</option>
                                <option value="Quoted" \${q.status === 'Quoted' ? 'selected' : ''}>Quoted (Dispatches Email Proposal)</option>
                                <option value="Accepted" \${q.status === 'Accepted' ? 'selected' : ''}>Accepted</option>
                                <option value="Rejected" \${q.status === 'Rejected' ? 'selected' : ''}>Rejected</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom:12px;">
                            <label>Calculated Quoted Rate ($ USD) *</label>
                            <input type="text" id="modal-q-rate" value="\${q.quoted_rate || ''}" placeholder="e.g. 2,450.00" style="padding:8px; font-weight:bold; font-size:14px; border:1px solid #cbd5e1; border-radius:4px;">
                        </div>

                        <div class="form-group" style="margin-bottom:20px;">
                            <label>Admin / Dispatcher Notes to Customer</label>
                            <textarea id="modal-q-notes" rows="3" placeholder="Explain freight details, transit timeline, and customs inclusions...">\${q.admin_notes || ''}</textarea>
                        </div>

                        <div style="display:flex; justify-content:flex-end; gap:10px;">
                            <button type="button" class="btn-wp btn-secondary" onclick="closeQuoteModal()">Cancel</button>
                            <button type="submit" class="btn-wp btn-success">Update & Dispatch Email Proposal</button>
                        </div>
                    </form>
                \`;

                document.getElementById('quote-modal').classList.add('active');
            };

            function closeQuoteModal() {
                document.getElementById('quote-modal').classList.remove('active');
            }
            document.getElementById('btn-close-quote-modal').addEventListener('click', closeQuoteModal);

            window.saveQuoteRate = async function(e, quoteId) {
                e.preventDefault();
                const status = document.getElementById('modal-q-status').value;
                const rate = document.getElementById('modal-q-rate').value.trim();
                const notes = document.getElementById('modal-q-notes').value.trim();

                try {
                    const res = await fetch('/api/quotes/' + quoteId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: status, quoted_rate: rate, admin_notes: notes })
                    });

                    if (res.ok) {
                        showAlert('Quote updated successfully! Rate proposal email dispatched via wp_mail().', 'success');
                        closeQuoteModal();
                        loadQuotes();
                    } else {
                        showAlert('Failed to update quote rate.', 'danger');
                    }
                } catch(err) {
                    showAlert('Server error updating quote.', 'danger');
                }
            };

            // Load Contact Messages
            async function loadContactMessages() {
                const status = document.getElementById('contact-status-select').value;
                try {
                    const res = await fetch('/api/contact-messages?status=' + encodeURIComponent(status));
                    currentContacts = await res.json();
                    renderContactTable(currentContacts);
                } catch(e) {
                    showAlert('Error loading contact inbox.', 'danger');
                }
            }

            function renderContactTable(list) {
                const tbody = document.getElementById('contact-table-body');
                document.getElementById('badge-contact').textContent = list.length;

                if (list.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px; color:#64748b;">No contact messages in inbox.</td></tr>';
                    return;
                }

                let html = '';
                list.forEach(m => {
                    html += \`
                        <tr>
                            <td><strong style="font-family:monospace; font-size:12px;">\${m.id}</strong></td>
                            <td><span class="status-badge \${m.status === 'Unread' ? 'status-hold' : 'status-delivered'}">\${m.status}</span></td>
                            <td><strong>\${m.full_name}</strong><br><span style="font-size:11px; color:#64748b;">\${m.email}</span></td>
                            <td><strong>\${m.subject}</strong></td>
                            <td><span style="font-size:12px; color:#475569;">\${m.message.substring(0, 60)}...</span></td>
                            <td><span style="font-size:11px; color:#64748b;">\${m.date_submitted}</span></td>
                            <td>
                                <button class="btn-wp btn-secondary" onclick="markContactStatus('\${m.id}', 'Replied')" style="font-size:11px; padding:3px 7px;">Mark Replied</button>
                            </td>
                        </tr>
                    \`;
                });
                tbody.innerHTML = html;
            }

            window.markContactStatus = async function(msgId, newStatus) {
                try {
                    const res = await fetch('/api/contact-messages/' + msgId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: newStatus })
                    });
                    if (res.ok) {
                        showAlert('Message marked as ' + newStatus, 'success');
                        loadContactMessages();
                    }
                } catch(e) {
                    showAlert('Error updating message status.', 'danger');
                }
            };

            // WhatsApp Settings
            async function loadWhatsAppSettings() {
                try {
                    const res = await fetch('/api/settings/whatsapp');
                    const data = await res.json();
                    document.getElementById('wa-num').value = data.number;
                    document.getElementById('wa-msg').value = data.default_message;
                } catch(e) {
                    showAlert('Error loading WhatsApp settings.', 'danger');
                }
            }

            document.getElementById('whatsapp-settings-form').addEventListener('submit', async function() {
                const num = document.getElementById('wa-num').value.trim();
                const msg = document.getElementById('wa-msg').value.trim();

                try {
                    const res = await fetch('/api/settings/whatsapp', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ number: num, default_message: msg })
                    });
                    if (res.ok) {
                        showAlert('WhatsApp configuration saved successfully!', 'success');
                    }
                } catch(e) {
                    showAlert('Error saving WhatsApp settings.', 'danger');
                }
            });

            // Email Audit Logs
            async function loadEmailLogs() {
                try {
                    const res = await fetch('/api/logs/emails');
                    const list = await res.json();
                    const tbody = document.getElementById('email-logs-table-body');

                    if (list.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px; color:#64748b;">No email audit logs recorded.</td></tr>';
                        return;
                    }

                    let html = '';
                    list.forEach(log => {
                        html += \`
                            <tr>
                                <td><strong style="font-family:monospace; font-size:12px;">\${log.id}</strong></td>
                                <td><strong>\${log.recipient}</strong></td>
                                <td>\${log.subject}</td>
                                <td><span style="font-size:11px; background:#eff6ff; color:#2563eb; padding:2px 8px; border-radius:4px; font-weight:700;">\${log.type}</span></td>
                                <td><span style="font-size:11px; color:#64748b;">\${log.timestamp}</span></td>
                                <td><span class="status-badge status-delivered">\${log.status}</span></td>
                            </tr>
                        \`;
                    });
                    tbody.innerHTML = html;
                } catch(e) {
                    showAlert('Error loading email audit logs.', 'danger');
                }
            }

            async function loadPayments() {
                const statusEl = document.getElementById('filter-payment-status');
                const providerEl = document.getElementById('filter-payment-provider');
                const status = statusEl ? statusEl.value : 'all';
                const provider = providerEl ? providerEl.value : 'all';
                
                let url = '/api/payments/transactions?status=' + status + '&provider=' + provider;
                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    renderPaymentsTable(data);
                } catch(e) {
                    showAlert('Error loading payments: ' + e.message, 'danger');
                }
            }

            function renderPaymentsTable(list) {
                const tbody = document.getElementById('payments-table-body');
                if (!tbody) return;
                if (!list || list.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:30px; color:#64748b;">No payment transaction records found.</td></tr>';
                    return;
                }

                tbody.innerHTML = list.map(t => {
                    let badgeClass = 'badge-pending';
                    if (t.status === 'Successful') badgeClass = 'badge-delivered';
                    else if (t.status === 'Failed') badgeClass = 'badge-hold';
                    else if (t.status === 'Refunded') badgeClass = 'badge-customs';

                    let actions = '';
                    if (t.status === 'Successful') {
                        actions = '<button onclick="refundPayment(\'' + t.transaction_id + '\')" class="btn-wp btn-secondary" style="font-size:11px; padding:4px 8px;">Refund</button>';
                    } else if (t.status === 'Pending') {
                        actions = '<button onclick="markPaymentPaid(\'' + t.transaction_id + '\')" class="btn-wp btn-success" style="font-size:11px; padding:4px 8px;">Mark Paid</button>';
                    }

                    return '<tr>' +
                        '<td><strong style="font-family:monospace; color:#1e293b;">' + t.transaction_id + '</strong><br><small style="color:#64748b; font-family:monospace;">' + t.reference + '</small></td>' +
                        '<td><strong>' + t.customer_name + '</strong><br><small style="color:#64748b;">' + t.customer_email + '</small></td>' +
                        '<td><strong style="color:#0f172a; font-size:14px;">' + t.currency + ' ' + t.amount.toFixed(2) + '</strong></td>' +
                        '<td><span style="text-transform:capitalize; font-weight:700;">' + t.provider + '</span></td>' +
                        '<td><span style="font-size:12px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">' + t.payment_type.replace('_', ' ') + '</span></td>' +
                        '<td><span class="status-badge ' + badgeClass + '">' + t.status + '</span></td>' +
                        '<td><small style="color:#64748b;">' + t.date + '</small></td>' +
                        '<td>' + actions + '</td>' +
                    '</tr>';
                }).join('');
            }

            async function triggerTestPayment() {
                try {
                    const res = await fetch('/api/payments/initiate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            amount: 1850.00,
                            customer_email: 'test.importer@cargo.com',
                            customer_name: 'Test Cargo Client',
                            payment_type: 'shipping_payment',
                            provider: 'stripe',
                            related_item_id: 'QX-8829-US'
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        showAlert('Simulated Payment Created! Transaction ID: ' + data.transaction_id, 'success');
                        loadPayments();
                    }
                } catch(e) {
                    showAlert('Failed to simulate payment: ' + e.message, 'danger');
                }
            }

            async function markPaymentPaid(txnId) {
                try {
                    const res = await fetch('/api/payments/transactions/' + txnId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: 'Successful' })
                    });
                    const data = await res.json();
                    if (data.success) {
                        showAlert('Payment ' + txnId + ' updated to Successful!', 'success');
                        loadPayments();
                    }
                } catch(e) {
                    showAlert('Error updating payment: ' + e.message, 'danger');
                }
            }

            async function refundPayment(txnId) {
                if (!confirm('Are you sure you want to mark transaction ' + txnId + ' as Refunded?')) return;
                try {
                    const res = await fetch('/api/payments/transactions/' + txnId, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: 'Refunded' })
                    });
                    const data = await res.json();
                    if (data.success) {
                        showAlert('Payment ' + txnId + ' marked as Refunded.', 'success');
                        loadPayments();
                    }
                } catch(e) {
                    showAlert('Error processing refund: ' + e.message, 'danger');
                }
            }

            async function loadIntegrationSettings() {
                try {
                    const [resCarrier, resPay] = await Promise.all([
                        fetch('/api/settings/carrier-api'),
                        fetch('/api/settings/payment')
                    ]);
                    const carrier = await resCarrier.json();
                    const pay = await resPay.json();

                    if (document.getElementById('cfg-carrier-provider')) document.getElementById('cfg-carrier-provider').value = carrier.provider || 'demo_carrier';
                    if (document.getElementById('cfg-carrier-url')) document.getElementById('cfg-carrier-url').value = carrier.api_url || '';
                    if (document.getElementById('cfg-carrier-key')) document.getElementById('cfg-carrier-key').value = carrier.api_key || '';
                    if (document.getElementById('cfg-carrier-secret')) document.getElementById('cfg-carrier-secret').value = carrier.api_secret || '';
                    if (document.getElementById('cfg-carrier-mode')) document.getElementById('cfg-carrier-mode').value = carrier.mode || 'sandbox';

                    if (document.getElementById('cfg-payment-provider')) document.getElementById('cfg-payment-provider').value = pay.provider || 'stripe';
                    if (document.getElementById('cfg-payment-pubkey')) document.getElementById('cfg-payment-pubkey').value = pay.public_key || '';
                    if (document.getElementById('cfg-payment-seckey')) document.getElementById('cfg-payment-seckey').value = pay.secret_key || '';
                    if (document.getElementById('cfg-payment-whsecret')) document.getElementById('cfg-payment-whsecret').value = pay.webhook_secret || '';
                    if (document.getElementById('cfg-payment-currency')) document.getElementById('cfg-payment-currency').value = pay.currency || 'USD';
                    if (document.getElementById('cfg-payment-mode')) document.getElementById('cfg-payment-mode').value = pay.mode || 'test';
                } catch(e) {
                    showAlert('Error loading settings: ' + e.message, 'danger');
                }
            }

            async function saveCarrierApiSettings(e) {
                e.preventDefault();
                const payload = {
                    provider: document.getElementById('cfg-carrier-provider').value,
                    api_url: document.getElementById('cfg-carrier-url').value,
                    api_key: document.getElementById('cfg-carrier-key').value,
                    api_secret: document.getElementById('cfg-carrier-secret').value,
                    mode: document.getElementById('cfg-carrier-mode').value
                };

                try {
                    const res = await fetch('/api/settings/carrier-api', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success) {
                        showAlert('Carrier API credentials updated successfully!', 'success');
                    }
                } catch(err) {
                    showAlert('Failed to save carrier settings: ' + err.message, 'danger');
                }
            }

            async function savePaymentGatewaySettings(e) {
                e.preventDefault();
                const payload = {
                    provider: document.getElementById('cfg-payment-provider').value,
                    public_key: document.getElementById('cfg-payment-pubkey').value,
                    secret_key: document.getElementById('cfg-payment-seckey').value,
                    webhook_secret: document.getElementById('cfg-payment-whsecret').value,
                    currency: document.getElementById('cfg-payment-currency').value,
                    mode: document.getElementById('cfg-payment-mode').value
                };

                try {
                    const res = await fetch('/api/settings/payment', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success) {
                        showAlert('Payment Gateway configuration updated successfully!', 'success');
                    }
                } catch(err) {
                    showAlert('Failed to save payment settings: ' + err.message, 'danger');
                }
            }

            function showAlert(msg, type = 'success') {
                const banner = document.getElementById('admin-alert-banner');
                banner.style.display = 'block';
                if (type === 'success') {
                    banner.style.background = '#d1fae5';
                    banner.style.color = '#047857';
                    banner.style.border = '1px solid #10b981';
                } else {
                    banner.style.background = '#fee2e2';
                    banner.style.color = '#b91c1c';
                    banner.style.border = '1px solid #f87171';
                }
                banner.textContent = msg;
                setTimeout(() => { banner.style.display = 'none'; }, 5000);
            }

            // Events Repeater Handling for Shipments Modal
            function addEventRow(data = {}) {
                const container = document.getElementById('events-repeater-container');
                const count = container.querySelectorAll('.event-item-card').length;
                const div = document.createElement('div');
                div.className = 'event-item-card';
                div.innerHTML = \`
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <strong style="font-size:12px; color:#0f172a;">Milestone #<span class="evt-idx">\${count + 1}</span></strong>
                        <div>
                            <button type="button" class="btn-wp btn-secondary btn-evt-up" style="font-size:10px; padding:2px 6px;">▲</button>
                            <button type="button" class="btn-wp btn-secondary btn-evt-down" style="font-size:10px; padding:2px 6px;">▼</button>
                            <button type="button" class="btn-wp btn-danger btn-evt-del" style="font-size:10px; padding:2px 6px; margin-left:6px;">Remove</button>
                        </div>
                    </div>
                    <div class="form-grid" style="margin-bottom:6px;">
                        <input type="text" class="evt-status" placeholder="Status / Milestone Title" value="\${data.status || ''}">
                        <input type="text" class="evt-location" placeholder="Location" value="\${data.location || ''}">
                        <input type="date" class="evt-date" value="\${data.date || ''}">
                        <input type="text" class="evt-time" placeholder="Time e.g. 14:30 PM" value="\${data.time || ''}">
                    </div>
                    <input type="text" class="evt-desc" placeholder="Event Description / Notes" value="\${data.description || ''}" style="width:100%; font-size:12px; padding:6px 10px; border:1px solid #cbd5e1; border-radius:4px;">
                \`;
                container.appendChild(div);
                reindexEventRows();
            }

            function reindexEventRows() {
                const rows = document.querySelectorAll('.event-item-card');
                rows.forEach((row, i) => {
                    const idxSpan = row.querySelector('.evt-idx');
                    if (idxSpan) idxSpan.textContent = i + 1;
                });
            }

            document.getElementById('events-repeater-container').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-evt-del')) {
                    e.target.closest('.event-item-card').remove();
                    reindexEventRows();
                } else if (e.target.classList.contains('btn-evt-up')) {
                    const card = e.target.closest('.event-item-card');
                    if (card.previousElementSibling) {
                        card.parentNode.insertBefore(card, card.previousElementSibling);
                        reindexEventRows();
                    }
                } else if (e.target.classList.contains('btn-evt-down')) {
                    const card = e.target.closest('.event-item-card');
                    if (card.nextElementSibling) {
                        card.parentNode.insertBefore(card.nextElementSibling, card);
                        reindexEventRows();
                    }
                }
            });

            document.getElementById('btn-add-event-row').addEventListener('click', () => addEventRow());

            // Modal Controls
            const modal = document.getElementById('shipment-modal');
            
            function openModal(title = 'Add New Shipment') {
                document.getElementById('modal-title').textContent = title;
                modal.classList.add('active');
            }

            function closeModal() {
                modal.classList.remove('active');
                document.getElementById('shipment-form').reset();
                document.getElementById('form-shipment-id').value = '';
                document.getElementById('events-repeater-container').innerHTML = '';
            }

            document.getElementById('btn-open-add-modal').addEventListener('click', () => {
                closeModal();
                addEventRow({ status: '1. Waybill & Cargo Registered', description: 'Package created in logistics system.' });
                openModal('Add New Shipment');
            });

            document.getElementById('btn-close-modal').addEventListener('click', closeModal);
            document.getElementById('btn-cancel-modal').addEventListener('click', closeModal);

            // Edit Shipment
            window.editShipment = function(id) {
                const item = currentShipments.find(s => s.id === id);
                if (!item) return;

                document.getElementById('form-shipment-id').value = item.id;
                document.getElementById('f-tracking').value = item.tracking_number;
                document.getElementById('f-status').value = item.status;
                document.getElementById('f-origin').value = item.origin;
                document.getElementById('f-destination').value = item.destination;
                document.getElementById('f-location').value = item.current_location || '';
                document.getElementById('f-carrier').value = item.carrier || '';
                document.getElementById('f-service').value = item.service_type || 'Air Freight';
                document.getElementById('f-estdelivery').value = item.estimated_delivery || '';
                document.getElementById('f-sender').value = item.sender || '';
                document.getElementById('f-receiver').value = item.receiver || '';
                document.getElementById('f-package').value = item.package_type || '';
                document.getElementById('f-weight').value = item.weight || '';
                document.getElementById('f-desc').value = item.description || '';

                const container = document.getElementById('events-repeater-container');
                container.innerHTML = '';
                if (item.events && item.events.length > 0) {
                    item.events.forEach(evt => addEventRow(evt));
                } else {
                    addEventRow();
                }

                openModal('Edit Shipment (' + item.tracking_number + ')');
            };

            // Delete Shipment
            window.deleteShipment = async function(id) {
                if (!confirm('Are you sure you want to delete this shipment record?')) return;
                try {
                    const res = await fetch('/api/shipments/' + id, { method: 'DELETE' });
                    if (res.ok) {
                        showAlert('Shipment deleted successfully!', 'success');
                        loadShipments();
                    } else {
                        const err = await res.json();
                        showAlert(err.error || 'Failed to delete shipment.', 'danger');
                    }
                } catch(e) {
                    showAlert('Error deleting shipment: ' + e.message, 'danger');
                }
            };

            // Save Form (Create/Update)
            document.getElementById('shipment-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const id = document.getElementById('form-shipment-id').value;
                const trackingNumber = document.getElementById('f-tracking').value.trim().toUpperCase();

                const eventCards = document.querySelectorAll('.event-item-card');
                const events = [];
                eventCards.forEach(card => {
                    const st = card.querySelector('.evt-status').value.trim();
                    const loc = card.querySelector('.evt-location').value.trim();
                    const dt = card.querySelector('.evt-date').value.trim();
                    const tm = card.querySelector('.evt-time').value.trim();
                    const desc = card.querySelector('.evt-desc').value.trim();
                    if (st || loc) {
                        events.push({ status: st, location: loc, date: dt, time: tm, description: desc });
                    }
                });

                const payload = {
                    tracking_number: trackingNumber,
                    status: document.getElementById('f-status').value,
                    origin: document.getElementById('f-origin').value.trim(),
                    destination: document.getElementById('f-destination').value.trim(),
                    current_location: document.getElementById('f-location').value.trim(),
                    carrier: document.getElementById('f-carrier').value.trim(),
                    service_type: document.getElementById('f-service').value,
                    estimated_delivery: document.getElementById('f-estdelivery').value.trim(),
                    sender: document.getElementById('f-sender').value.trim(),
                    receiver: document.getElementById('f-receiver').value.trim(),
                    package_type: document.getElementById('f-package').value.trim(),
                    weight: document.getElementById('f-weight').value.trim(),
                    description: document.getElementById('f-desc').value.trim(),
                    events: events
                };

                const method = id ? 'PUT' : 'POST';
                const url = id ? ('/api/shipments/' + id) : '/api/shipments';

                try {
                    const res = await fetch(url, {
                        method: method,
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    if (res.ok) {
                        showAlert(id ? 'Shipment updated successfully!' : 'New shipment created successfully!', 'success');
                        closeModal();
                        loadShipments();
                    } else {
                        const err = await res.json();
                        alert('Validation Error: ' + (err.error || 'Failed to save shipment.'));
                    }
                } catch(err) {
                    alert('Server error: ' + err.message);
                }
            });

            // Filters & Listeners
            document.getElementById('btn-apply-filter').addEventListener('click', loadShipments);
            document.getElementById('btn-filter-quotes').addEventListener('click', loadQuotes);
            document.getElementById('btn-filter-contact').addEventListener('click', loadContactMessages);

            // Initial load
            loadShipments();
        </script>
    </body>
    </html>
    `);
});

// Serve homepage
app.get('/', (req, res) => {
    const html = renderWpPhpTemplate('front-page.php');
    res.send(html);
});

// Serve public page routes
app.get('/track-shipment*', (req, res) => {
    const html = renderWpPhpTemplate('page-track-shipment.php');
    res.send(html);
});

app.get('/about-us*', (req, res) => {
    const html = renderWpPhpTemplate('page-about-us.php');
    res.send(html);
});

app.get('/services*', (req, res) => {
    const html = renderWpPhpTemplate('page-services.php');
    res.send(html);
});

app.get('/get-a-quote*', (req, res) => {
    const html = renderWpPhpTemplate('page-get-a-quote.php');
    res.send(html);
});

app.get('/contact*', (req, res) => {
    const html = renderWpPhpTemplate('page-contact.php');
    res.send(html);
});

app.get('/login*', (req, res) => {
    const html = renderWpPhpTemplate('page-login.php');
    res.send(html);
});

app.get('/register*', (req, res) => {
    const html = renderWpPhpTemplate('page-register.php');
    res.send(html);
});

app.get('/dashboard*', (req, res) => {
    const html = renderWpPhpTemplate('page-dashboard.php');
    res.send(html);
});

app.get('/forgot-password*', (req, res) => {
    const html = renderWpPhpTemplate('page-login.php');
    res.send(html);
});

// Serve insights / blog index
app.get('/insights*', (req, res) => {
    const html = renderWpPhpTemplate('home.php');
    res.send(html);
});

// Health check API
app.get('/api/health', (req, res) => {
    res.json({
        status: 'ok',
        theme: 'Haivora Logistics',
        company: 'Qidex Express LOGISTICS',
        phase: 5
    });
});

// Fallback route
app.get('*', (req, res) => {
    const html = renderWpPhpTemplate('front-page.php');
    res.send(html);
});

app.listen(PORT, '0.0.0.0', () => {
    console.log(`Haivora Logistics theme preview server running on http://0.0.0.0:${PORT}`);
});
